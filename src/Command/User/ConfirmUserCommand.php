<?php

declare(strict_types=1);

namespace App\Command\User;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ConfirmUserCommand extends Command
{
    private $users;
    private $flusher;

    public function __construct(UserRepository $users, Flusher $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('user:confirm')
            ->setDescription('This command confirmed selected user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $email = $helper->ask($input, $output, new Question('Enter user email: '));

        /** @var User $user */
        $user = $this->users->getByEmail(new Email($email));
        $user->confirmSignUp();

        $this->flusher->flush();

        $output->writeln('<info>User succesfuly confirmed!</info>');

        return 1;
    }
}
