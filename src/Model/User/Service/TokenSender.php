<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\ResetToken;
use RuntimeException;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;

class TokenSender
{
    private $mailer;
    private $twig;

    public function __construct(Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendConfirmationToken(Email $email, string $token): void
    {
        $message = (new Swift_Message('Sig Up Confirmation'))
            ->setTo($email->getValue())
            ->setBody($this->twig->render('mail/user/signup.html.twig', [
                'token' => $token,
            ]), 'text/html');

        if (!$this->mailer->send($message)) {
            throw new RuntimeException('Unable to send message.');
        }
    }

    public function sendResetToken(Email $email, ResetToken $token): void
    {
        $message = (new Swift_Message('Password resetting'))
            ->setTo($email->getValue())
            ->setBody($this->twig->render('mail/user/reset.html.twig', [
                'token' => $token->getToken(),
            ]), 'text/html');

        if (!$this->mailer->send($message)) {
            throw new RuntimeException('Unable to send message.');
        }
    }

    public function sendEmailToken(Email $email, string $token): void
    {
        $message = (new \Swift_Message('Email Confirmation'))
            ->setTo($email->getValue())
            ->setBody($this->twig->render('mail/user/email.html.twig', [
                'token' => $token,
            ]), 'text/html');

        if (!$this->mailer->send($message)) {
            throw new RuntimeException('Unable to send message.');
        }
    }
}
