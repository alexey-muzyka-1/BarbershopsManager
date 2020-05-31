<?php

declare(strict_types=1);

namespace App\Model\Barbershop\Entity\KondorceChoice;

class KondorceChoice
{
    private $arrayLength;
    private $outputResults;

    public function __construct($arrayLength = 5, $outputResults = false)
    {
        $this->arrayLength = $arrayLength;
        $this->outputResults = $outputResults;
    }

    public function findBestVariant(array $preferenceChoicies): int
    {
        $preferenceTable = $this->createPreferenceTable($preferenceChoicies);
        $comparisonTable = $this->createComparisonTable($preferenceTable);
        $number = $this->findKondorceNumber($comparisonTable);

        if ($this->outputResults) {
            $this->outputPreferenceTable($preferenceTable);
            $this->outputComparisonTable($comparisonTable);
        }

        return $number;
    }

    private function findKondorceNumber(array $m): int
    {
        $n = 0;
        for ($i = 0; $i < $this->arrayLength; ++$i) {
            for ($j = 0; $j < $this->arrayLength; ++$j) {
                if ($m[$i][$j] >= $m[$j][$i] && $i != $j) {
                    ++$n;
                }
                if ($this->arrayLength - 1 == $j) {
                    if ($this->arrayLength - 1 == $n) {
                        return $i + 1;
                    } else {
                        $n = 0;
                    }
                }
            }
        }

        throw new \DomainException('Cannot find best variant!');
    }

    private function createComparisonTable(array $preferenceTable): array
    {
        $comparisonTable = $this->createEmptyArray();
        for ($k = 0; $k < $this->arrayLength; ++$k) {
            for ($i = 0; $i < $this->arrayLength; ++$i) {
                for ($j = 0; $j < $this->arrayLength; ++$j) {
                    if ($preferenceTable[$k][$j] < $preferenceTable[$i][$j] && $i != $k) {
                        ++$comparisonTable[$k][$i];
                    }
                }
            }
        }

        return $comparisonTable;
    }

    private function outputComparisonTable(array $comprassionTable): void
    {
        for ($i = 0; $i < $this->arrayLength; ++$i) {
            for ($j = 0; $j < $this->arrayLength; ++$j) {
                if ($i != $j) {
                    echo $comprassionTable[$i][$j].' ';
                } else {
                    echo '- ';
                }
            }
            echo PHP_EOL;
        }
    }

    private function createPreferenceTable(array $preferenceChoicies): array
    {
        $p = $this->createEmptyArray();
        for ($k = 0; $k < $this->arrayLength; ++$k) {
            for ($i = 0; $i < $this->arrayLength; ++$i) {
                for ($j = 0; $j < $this->arrayLength; ++$j) {
                    if ($preferenceChoicies[$j][$i] == ($k + 1)) {
                        $p[$k][$i] = $j + 1;
                    }
                }
            }
        }

        return $p;
    }

    private function outputPreferenceTable(array $preferenceTable): void
    {
        for ($i = 0; $i < $this->arrayLength; ++$i) {
            for ($j = 0; $j < $this->arrayLength; ++$j) {
                echo $preferenceTable[$i][$j].' ';
            }
            echo PHP_EOL;
        }
        echo PHP_EOL;
    }

    /*
     * make like this just for fun :)
     * This function create arrays like:
     *
     *      [0, 0, 0],
     *      [0, 0, 0],
     *      [0, 0, 0],
     *
     * Length of empty array depends on `$this->arrayLength`
     */
    private function createEmptyArray(): array
    {
        return array_fill(0, $this->arrayLength, array_fill(0, $this->arrayLength, 0));
    }
}
