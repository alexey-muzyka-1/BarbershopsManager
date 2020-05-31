<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable
 */
class Name
{
    /**
     * @var string
     * @ORM\Column(type="string", name="first")
     */
    private $firstName;
    /**
     * @var string
     * @ORM\Column(type="string", name="last")
     */
    private $lastName;

    public function __construct(string $first, string $last)
    {
        Assert::notEmpty($first);
        Assert::notEmpty($last);

        $this->firstName = $first;
        $this->lastName = $last;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFull(): string
    {
        return $this->firstName.' '.$this->lastName;
    }
}
