<?php

declare(strict_types=1);

namespace App\Model\Barbershop\Entity\Company;

use App\Model\Barbershop\Constants\CompanyError;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

class CompanyRepository
{
    /**
     * @var EntityRepository
     */
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Company::class);
        $this->em = $em;
    }

    public function get(Id $id): Company
    {
        /** @var Company $company */
        if (!$company = $this->repo->find($id->getValue())) {
            throw new DomainException(CompanyError::COMPANY_DOSENT_EXISTS);
        }
        return $company;
    }

    public function hasByName(string $name): bool
    {
        return $this->repo->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->andWhere('c.name = :name')
            ->setParameter(':name', $name)
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function add(Company $company): void
    {
        $this->em->persist($company);
    }


    public function remove(Company $company): void
    {
        $this->em->remove($company);
    }
}