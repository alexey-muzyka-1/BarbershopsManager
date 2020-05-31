<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use App\Model\User\UserErrorConstants;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user_users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"}),
 *     @ORM\UniqueConstraint(columns={"reset_token_token"})
 * })
 */
class User
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_WAIT = 'wait';
    public const STATUS_BLOCKED = 'blocked';

    /**
     * @var Id
     * @ORM\Id
     * @ORM\Column(type="user_id")
     */
    private $id;

    /**
     * @var Email|null
     * @ORM\Column(type="user_email", nullable=true)
     */
    private $email;

    /**
     * @var Name
     * @ORM\Embedded(class="Name")
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(type="string", name="password_hash", nullable=true)
     */
    private $passwordHash;

    /**
     * @var string|null
     * @ORM\Column(type="string", name="confirm_token", nullable=true)
     */
    private $token;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="created_at")
     */
    private $createdAt;

    /**
     * @var ResetToken|null
     * @ORM\Embedded(class="ResetToken", columnPrefix="reset_token_")
     */
    private $resetToken;

    /**
     * @var Email|null
     * @ORM\Column(type="user_email", name="new_email", nullable=true)
     */
    private $newEmail;

    /**
     * @var string|null
     * @ORM\Column(type="string", name="new_email_token", nullable=true)
     */
    private $newEmailToken;

    /**
     * @var Network[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Network", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $networks;

    /**
     * @var Role
     * @ORM\Column(type="user_role", length=16)
     */
    private $role;

    /**
     * @var string
     * @ORM\Column(type="string", length=16, name="is_active")
     */
    private $isActive = self::STATUS_WAIT;

    private function __construct(Id $id, DateTimeImmutable $createdAt, Name $name)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->name = $name;
        $this->role = Role::user();
        $this->networks = new ArrayCollection();
    }

    public static function create(Id $id, DateTimeImmutable $date, Name $name, Email $email, string $hash): self
    {
        $user = new self($id, $date, $name);
        $user->email = $email;
        $user->passwordHash = $hash;
        $user->isActive = self::STATUS_ACTIVE;

        return $user;
    }

    public static function signUpByEmail(Id $id, Email $email, Name $name, string $hash, DateTimeImmutable $date, string $token): self
    {
        $user = new self($id, $date, $name);
        $user->email = $email;
        $user->passwordHash = $hash;
        $user->token = $token;
        $user->isActive = self::STATUS_WAIT;

        return $user;
    }

    public static function signUpByNetwork(Id $id, DateTimeImmutable $date, Name $name, string $network, string $identity): self
    {
        $user = new self($id, $date, $name);
        $user->addNetwork($network, $identity);
        $user->isActive = self::STATUS_ACTIVE;

        return $user;
    }

    public function addNetwork(string $network, string $identity): void
    {
        if ($this->isNetworkExists($network)) {
            $this->networks->add(new Network($this, $network, $identity));
        }
    }

    public function detachNetwork(string $network, string $identity): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->isFor($network, $identity)) {
                if (!$this->email && 1 === $this->networks->count()) {
                    throw new DomainException(UserErrorConstants:: USER_LAST_IDENTITY);
                }
                $this->networks->removeElement($existing);

                return;
            }
        }
        throw new DomainException(UserErrorConstants::USER_NETWORK_IS_NOT_ATTACHED);
    }

    public function edit(Email $email, Name $name): void
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function confirmSignUp(): void
    {
        if ($this->isActive()) {
            throw new DomainException(UserErrorConstants::USER_ALREADY_CONFIRMED);
        }
        $this->isActive = self::STATUS_ACTIVE;
        $this->token = null;
    }

    public function isNetworkExists(string $network): bool
    {
        foreach ($this->networks as $existing) {
            if ($existing->isNetworkEquals($network)) {
                throw new DomainException(UserErrorConstants::USER_NETWORK_ATTACHED);
            }
        }

        return true;
    }

    public function requestEmailChanging(Email $email, string $token): void
    {
        if (!$this->isActive()) {
            throw new DomainException(UserErrorConstants::USER_IS_NOT_ACTIVE);
        }
        if ($this->email && $this->email->isEqual($email)) {
            throw new DomainException(UserErrorConstants::USER_INCORRECT_EMAIL);
        }
        $this->newEmail = $email;
        $this->newEmailToken = $token;
    }

    public function confirmEmailChanging(string $token): void
    {
        if (!$this->newEmailToken) {
            throw new DomainException(UserErrorConstants:: USER_CHANGING_IS_NOT_REQUESTED);
        }
        if ($this->newEmailToken !== $token) {
            throw new DomainException(UserErrorConstants:: USER_INCORRECT_TOKEN);
        }
        $this->email = $this->newEmail;
        $this->newEmail = null;
        $this->newEmailToken = null;
    }

    public function requestPasswordReset(ResetToken $token, DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new DomainException(UserErrorConstants::USER_IS_NOT_ACTIVE);
        }
        if (!$this->email) {
            throw new DomainException(UserErrorConstants::USER_EMAIL_IS_NOT_SPECIFIED);
        }
        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            throw new DomainException(UserErrorConstants::USER_RESET_IS_REQUESTED);
        }
        $this->resetToken = $token;
    }

    public function passwordReset(DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) {
            throw new DomainException(UserErrorConstants::USER_RESET_IS_NOT_REQUESTED);
        }
        if ($this->resetToken->isExpiredTo($date)) {
            throw new DomainException(UserErrorConstants::USER_TOKEN_IS_EXPIRED);
        }
        $this->passwordHash = $hash;
        $this->resetToken = null;
    }

    public function changeRole(Role $role): void
    {
        if ($this->role->isEqual($role)) {
            throw new DomainException(UserErrorConstants::USER_EQUALS_ROLES);
        }
        $this->role = $role;
    }

    public function changeName(Name $name): void
    {
        $this->name = $name;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new DomainException(UserErrorConstants::USER_IS_ALREADY_ACTIVE);
        }
        $this->isActive = self::STATUS_ACTIVE;
    }

    public function block(): void
    {
        if ($this->isBlocked()) {
            throw new DomainException(UserErrorConstants::USER_IS_ALREADY_BLOCKED);
        }
        $this->isActive = self::STATUS_BLOCKED;
    }

    /**
     * @ORM\PostLoad()
     */
    public function checkEmbeds(): void
    {
        if ($this->resetToken->isEmpty()) {
            $this->resetToken = null;
        }
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
    }

    public function isWait(): bool
    {
        return self::STATUS_WAIT === $this->isActive;
    }

    public function isActive(): bool
    {
        return self::STATUS_ACTIVE === $this->isActive;
    }

    public function isBlocked(): bool
    {
        return self::STATUS_BLOCKED === $this->isActive;
    }

    public function getIsActive(): string
    {
        return $this->isActive;
    }

    public function getNetworks(): array
    {
        return $this->networks->toArray();
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getNewEmail(): ?Email
    {
        return $this->newEmail;
    }

    public function getNewEmailToken(): ?string
    {
        return $this->newEmailToken;
    }
}
