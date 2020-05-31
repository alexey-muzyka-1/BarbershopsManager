<?php

declare(strict_types=1);

namespace App\Model\Barbershop\Entity\Barbershop;

use App\Model\Barbershop\Constants\BarbershopError;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

class BarbershopRepository
{
    /**
     * @var EntityRepository
     */
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Barbershop::class);
        $this->em = $em;
    }

    public function get(Id $id): Barbershop
    {
        if (!$barbershop = $this->repo->find($id->getValue())) {
            throw new DomainException(BarbershopError::BARBERSHOP_DOSENT_EXISTS);
        }

        return $barbershop;
    }

    public function hasByName(string $name): bool
    {
        return $this->repo->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->where('b.name = :name')
            ->setParameter(':name', $name)
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function add(Barbershop $barbershop): void
    {
        $this->em->persist($barbershop);
    }

    public function remove(Barbershop $barbershop): void
    {
        $this->em->remove($barbershop);
    }
}
