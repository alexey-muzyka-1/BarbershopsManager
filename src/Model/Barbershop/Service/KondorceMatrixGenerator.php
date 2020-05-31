<?php

declare(strict_types=1);

namespace App\Model\Barbershop\Service;

class KondorceMatrixGenerator
{
    private $choiceAmount;

    public function __construct(int $choiceAmount)
    {
        $this->choiceAmount = $choiceAmount;
    }

    public function randomKondorceMatrix(): array
    {
        $arr = $this->createSortedArray();
        $matrix = $this->createMatrix($arr);
        $matrix = $this->transposeMatrix($matrix);

        return $matrix;
    }

    private function createSortedArray(): array
    {
        $arr = [];
        for ($i = 0; $i < $this->choiceAmount; ++$i) {
            $arr[] = $i + 1;
        }

        return $arr;
    }

    private function createMatrix(array $arr): array
    {
        $matrix = [];
        for ($i = 0; $i < $this->choiceAmount; ++$i) {
            shuffle($arr);
            $matrix[] = $arr;
        }

        return $matrix;
    }

    private function transposeMatrix(array $arr): array
    {
        $transposedArr = [];
        for ($i = 0; $i < $this->choiceAmount; ++$i) {
            for ($j = 0; $j < $this->choiceAmount; ++$j) {
                $transposedArr[$j][$i] = $arr[$i][$j];
            }
        }

        return $transposedArr;
    }
}
