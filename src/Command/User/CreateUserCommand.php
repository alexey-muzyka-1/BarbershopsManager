<?php

declare(strict_types=1);

namespace App\Command\User;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\UseCase\SignUp\Request;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class CreateUserCommand extends Command
{
    private $users;
    private $handler;
    private $flusher;

    public function __construct(UserRepository $users, Request\Handler $handler, Flusher $flusher)
    {
        $this->users = $users;
        $this->handler = $handler;
        $this->flusher = $flusher;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('user:create')
            ->setDescription('This command create new user with selected params');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $command = new Request\Command();

        $helper = $this->getHelper('question');

        $command->email = $helper->ask($input, $output, new Question('Enter user email: '));
        $command->firstName = $helper->ask($input, $output, new Question('Enter user first name: '));
        $command->lastName = $helper->ask($input, $output, new Question('Enter user last name: '));
        $command->password = $helper->ask($input, $output, new Question('Enter user password: '));

        $this->handler->handle($command);

        $output->writeln('<info>User succesfuly created!</info>');

        if ($helper->ask($input, $output, new ConfirmationQuestion('Do you want to confirm user? [yes/no] ', false))
        ) {
            $this->confirmUser($output, $command->email);
        }

        return 1;
    }

    private function confirmUser(OutputInterface $output, string $email): void
    {
        $user = $this->users->getByEmail(new Email($email));
        $user->confirmSignUp();

        $this->flusher->flush();

        $output->writeln('<info>User succesfuly confirmed!</info>');
    }
}
