<?php

namespace App\Repository;

use App\Entity\AccessRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AccessRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessRole[]    findAll()
 * @method AccessRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessRoleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AccessRole::class);
    }

    // /**
    //  * @return AccessRole[] Returns an array of AccessRole objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccessRole
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
