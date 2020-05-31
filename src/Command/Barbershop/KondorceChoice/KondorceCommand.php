<?php

declare(strict_types=1);

namespace App\Command\Barbershop\KondorceChoice;

use App\Model\Barbershop\Entity\KondorceChoice\KondorceChoice;
use App\Model\Barbershop\Service\KondorceMatrixGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class KondorceCommand extends Command
{
    private $choiceAmount = 5;

    protected function configure(): void
    {
        $this->setName('kondorce:make-choice')
            ->setDescription('This command helps u to make hard decisions!!!');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->choiceAmount = (int) $this->getHelper('question')
            ->ask($input, $output, new Question('How many barbershops u would like to compare?: '));

        $barbershops = $this->getChoicies($input, $output);

        $matrixGenerator = new KondorceMatrixGenerator($this->choiceAmount);
        $kondorceChoice = new KondorceChoice($this->choiceAmount, true);
        $result = $kondorceChoice->findBestVariant($matrixGenerator->randomKondorceMatrix());

        $output->writeln('Your best variant is <info>'.$barbershops[$result - 1].'!</info> ');

        return 1;
    }

    private function getChoicies(InputInterface $input, OutputInterface $output): array
    {
        $barbershops = [];

        for ($i = 0; $i < $this->choiceAmount; ++$i) {
            $barbershops[] = $this->getHelper('question')->ask($input, $output, new Question('Enter barbershop name: '));
        }
        echo PHP_EOL;

        return $barbershops;
    }
}
