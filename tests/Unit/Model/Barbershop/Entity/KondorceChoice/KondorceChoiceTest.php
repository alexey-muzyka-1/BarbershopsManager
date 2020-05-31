<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Barbershop\Entity\KondorceChoice;

use App\Model\Barbershop\Entity\KondorceChoice\KondorceChoice;
use PHPUnit\Framework\TestCase;

class KondorceChoiceTest extends TestCase
{
    private const MATRIX_2 = [
        [1, 1],
        [2, 2],
    ];

    private const MATRIX_3 = [
        [3, 3, 3],
        [1, 1, 1],
        [2, 2, 2],
    ];

    private const MATRIX_4 = [
        [2, 2, 2, 2],
        [3, 3, 3, 3],
        [1, 1, 1, 1],
        [4, 4, 4, 4],
    ];

    private const MATRIX_5 = [
        [5, 5, 5, 5, 5],
        [2, 2, 2, 2, 2],
        [3, 3, 3, 3, 3],
        [1, 1, 1, 1, 1],
        [4, 4, 4, 4, 4],
    ];

    /**
     * @dataProvider dataKondorceChoice
     */
    public function testKondorceChoice(int $length, array $data, string $excepted): void
    {
        $kondorceChoice = new KondorceChoice($length);
        $value = $kondorceChoice->findBestVariant($data);

        $this->assertEquals($value, $excepted);
    }

    public function dataKondorceChoice(): array
    {
        return [
            [2, self::MATRIX_2, 1],
            [3, self::MATRIX_3, 3],
            [4, self::MATRIX_4, 2],
            [5, self::MATRIX_5, 5],
        ];
    }

}
