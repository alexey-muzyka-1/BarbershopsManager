<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Edit;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\UserErrorConstants;
use DomainException;

class Handler
{
    private $users;
    private $flusher;

    public function __construct(UserRepository $users, Flusher $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->getById(new Id($command->id));

        if ($this->users->hasByEmail($email = new Email($command->email))) {
            throw new DomainException(UserErrorConstants:: USER_EMAIL_IS_ALREADY_IN_USE);
        }

        $user->edit(
            $email,
            new Name(
                $command->firstName,
                $command->lastName
            )
        );

        $this->flusher->flush();
    }
}
