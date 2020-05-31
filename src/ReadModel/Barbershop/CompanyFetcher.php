<?php

namespace App\ReadModel\Barbershop;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;

class CompanyFetcher
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function all(): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'c.id',
                'c.name',
                'c.owner',
                '(SELECT COUNT(*) FROM barbershop_barbershop b WHERE b.company_id = c.id) AS barbershops'
            )
            ->from('barbershop_company', 'c')
            ->orderBy('name')
            ->execute();

        return $stmt->fetchAll(FetchMode::ASSOCIATIVE);
    }

    public function assoc(): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'name'
            )
            ->from('barbershop_company')
            ->orderBy('name')
            ->execute();

        return array_column($stmt->fetchAll(FetchMode::ASSOCIATIVE), 'name', 'id');
    }
}
