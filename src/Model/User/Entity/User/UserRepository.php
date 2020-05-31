<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use App\Model\User\UserErrorConstants;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

class UserRepository
{
    private $em;

    /**
     * @var EntityRepository
     */
    private $userRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->userRepository = $em->getRepository(User::class);
    }

    public function getById(Id $id): User
    {
        /** @var User $user */
        if (!$user = $this->userRepository->find($id->getValue())) {
            throw new DomainException(UserErrorConstants::USER_IS_NOT_FOUND);
        }

        return $user;
    }

    public function getByConfirmToken(string $token): User
    {
        /** @var User $user */
        if (!$user = $this->userRepository->findOneBy(['token' => $token])) {
            throw new DomainException(UserErrorConstants::USER_INCORRECT_CONFIRM_TOKEN);
        }

        return $user;
    }

    public function getByResetToken(string $token): User
    {
        /** @var User $user */
        if (!$user = $this->userRepository->findOneBy(['resetToken.token' => $token])) {
            throw new DomainException(UserErrorConstants::USER_INCORRECT_CONFIRM_TOKEN);
        }

        return $user;
    }

    public function getByEmail(Email $email): User
    {
        /** @var User $user */
        if (!$user = $this->userRepository->findOneBy(['email' => $email->getValue()])) {
            throw new DomainException(UserErrorConstants::USER_IS_NOT_FOUND);
        }

        return $user;
    }

    public function hasByEmail(Email $email): bool
    {
        return $this->userRepository->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->andWhere('u.email = :email')
                ->setParameter(':email', $email->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function hasByNetworkIdentity(string $network, string $identity): bool
    {
        return $this->userRepository->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->innerJoin('u.networks', 'n')
                ->andWhere('n.network = :network and n.identity = :identity')
                ->setParameter(':network', $network)
                ->setParameter(':identity', $identity)
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }
}
