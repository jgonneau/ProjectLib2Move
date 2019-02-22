<?php

namespace App\Repository;

use App\Entity\TypeOfVehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeOfVehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeOfVehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeOfVehicle[]    findAll()
 * @method TypeOfVehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeOfVehicleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeOfVehicle::class);
    }

    // /**
    //  * @return TypeOfVehicle[] Returns an array of TypeOfVehicle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeOfVehicle
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
