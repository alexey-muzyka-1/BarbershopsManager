<?php

declare(strict_types=1);

namespace App\Command\User;

use App\Model\User\Entity\User\Role as RoleValue;
use App\Model\User\UseCase\Role;
use App\ReadModel\User\UserFetcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ChangeRoleCommand extends Command
{
    private $users;
    private $validator;
    private $handler;

    public function __construct(UserFetcher $users, ValidatorInterface $validator, Role\Handler $handler)
    {
        $this->users = $users;
        $this->validator = $validator;
        $this->handler = $handler;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('user:change_role')
            ->setDescription('This command chenched current user role');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $email = $helper->ask($input, $output, new Question('Enter user email: '));

        $user = $this->users->getByEmail($email);

        $output->writeln('Current role: <info>'.$user->role.'</info>');

        $command = new Role\Command($user->id);
        $roles = $this->getAvailableRoles($user->role);

        $command->role = $helper->ask($input, $output, new ChoiceQuestion('Select role: ', $roles, 0));

        $violations = $this->validator->validate($command);

        if ($violations->count()) {
            foreach ($violations as $violation) {
                $output->writeln('<error>'.$violation->getPropertyPath().': '.$violation->getMessage().'</error>');
            }

            return 0;
        }

        $this->handler->handle($command);

        $output->writeln('<info>Done!</info>');

        return 1;
    }

    public function getAvailableRoles(string $currentRole): array
    {
        $roles = RoleValue::ALL_ROLES;
        $availableRoles = [];

        foreach ($roles as $role) {
            $role === $currentRole ?: $availableRoles[] = $role;
        }

        return $availableRoles;
    }
}
