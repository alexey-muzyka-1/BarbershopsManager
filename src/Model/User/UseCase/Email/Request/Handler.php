<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Email\Request;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\TokenSender;
use App\Model\User\Service\Tokenizer;
use App\Model\User\UserErrorConstants;
use DomainException;

class Handler
{
    private $users;
    private $tokenizer;
    private $sender;
    private $flusher;

    public function __construct(
        UserRepository $users,
        Tokenizer $tokenizer,
        TokenSender $sender,
        Flusher $flusher
    ) {
        $this->users = $users;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->getById(new Id($command->id));
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new DomainException(UserErrorConstants:: USER_EMAIL_IS_ALREADY_IN_USE);
        }

        $user->requestEmailChanging(
            $email,
            $token = $this->tokenizer->generate()
        );

        $this->flusher->flush();
        $this->sender->sendEmailToken($email, $token);
    }
}
