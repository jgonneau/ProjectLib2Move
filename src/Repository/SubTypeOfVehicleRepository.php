<?php

namespace App\Repository;

use App\Entity\SubTypeOfVehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SubTypeOfVehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubTypeOfVehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubTypeOfVehicle[]    findAll()
 * @method SubTypeOfVehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubTypeOfVehicleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SubTypeOfVehicle::class);
    }

    // /**
    //  * @return SubTypeOfVehicle[] Returns an array of SubTypeOfVehicle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubTypeOfVehicle
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
