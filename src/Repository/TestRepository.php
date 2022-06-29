<?php

namespace App\Repository;

use App\Entity\Test;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Test>
 *
 * @method Test|null find($id, $lockMode = null, $lockVersion = null)
 * @method Test|null findOneBy(array $criteria, array $orderBy = null)
 * @method Test[]    findAll()
 * @method Test[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Test::class);
    }

    public function add(Test $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Test $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllFilter($from , $to): array
    {
        $qb = $this->createQueryBuilder('t');
//        if($from instanceof \DateTime && $to instanceof \DateTime){
//            $qb->andWhere('t.beginTimestamp BETWEEN :beginFrom AND :beginTo');
//            $qb->setParameter('beginFrom', $from);
//            $qb->setParameter('beginTo', $to);
//        }else{
            if($from instanceof \DateTime){
                $qb->andWhere('t.beginTimestamp >= :beginFrom');
                $qb->setParameter('beginFrom', $from);
            }
            if($to instanceof \DateTime){
                $qb->andWhere('t.beginTimestamp <= :beginTo');
                $qb->setParameter('beginTo', $to);
            }
//        }

        return $qb->getQuery()->getResult();
    }
//    /**
//     * @return Test[] Returns an array of Test objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Test
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
