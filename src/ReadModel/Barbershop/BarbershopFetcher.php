<?php

declare(strict_types=1);

namespace App\ReadModel\Barbershop;

use App\ReadModel\Barbershop\Filter\Filter;
use Doctrine\DBAL\Connection;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use UnexpectedValueException;

class BarbershopFetcher
{
    private $connection;
    private $paginator;

    public function __construct(Connection $connection, PaginatorInterface $paginator)
    {
        $this->connection = $connection;
        $this->paginator = $paginator;
    }

    public function all(Filter $filter, int $page, int $size, string $sort, string $direction): PaginationInterface
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'b.id',
                'b.name',
                'CONCAT(b.adress_city, \' \', b.adress_street, \' \', b.adress_house) AS adress',
                'c.name as companyName',
                'b.status'
            )
            ->from('barbershop_barbershop', 'b')
            ->innerJoin('b', 'barbershop_company', 'c', 'b.company_id = c.id');

        if ($filter->name) {
            $qb->andWhere($qb->expr()->like('b.name', ':name'));
            $qb->setParameter(':name', '%'.mb_strtolower($filter->name).'%');
        }

        if ($filter->adress) {
            $qb->andWhere($qb->expr()->like('CONCAT(b.adress_city, \' \', b.adress_street, \' \', b.adress_house)', ':adress'));
            $qb->setParameter(':adress', '%'.mb_strtolower($filter->adress).'%');
        }

        if ($filter->status) {
            $qb->andWhere('b.status = :status');
            $qb->setParameter(':status', $filter->status);
        }

        if ($filter->companyName) {
            $qb->andWhere('b.company_id = :company');
            $qb->setParameter(':company', $filter->companyName);
        }

        if (!\in_array($sort, ['name', 'adress', 'companyName', 'status'], true)) {
            throw new UnexpectedValueException('Cannot sort by '.$sort);
        }

        $qb->orderBy($sort, 'desc' === $direction ? 'desc' : 'asc');

        return $this->paginator->paginate($qb, $page, $size);
    }

    public function exists(string $id): bool
    {
        return $this->connection->createQueryBuilder()
                ->select('COUNT(id)')
                ->from('barbershop_barbershop')
                ->where('id = :id')
                ->setParameter(':id', $id)
                ->execute()->fetchColumn() > 0;
    }
}
