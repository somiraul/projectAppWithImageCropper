<?php

namespace App\Repository;

use App\Entity\TestImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TestImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestImages[]    findAll()
 * @method TestImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestImagesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TestImages::class);
    }

    // /**
    //  * @return TestImages[] Returns an array of TestImages objects
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
    public function findOneBySomeField($value): ?TestImages
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
