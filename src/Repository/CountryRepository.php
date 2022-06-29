<?php

namespace App\Repository;

use App\Entity\Country;
use App\Entity\Test;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Country>
 *
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    public function add(Country $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Country $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getRandomCountries(int $number = 5): array
    {
        $all = $this->findAll();

        if(sizeof($all) < $number){
            return $all;
        }
        $arr = array();
        while(sizeof($arr) < 5){
            $i = random_int(0,sizeof($all)-1);
            if(in_array($all[$i], $arr)){
                continue;
            }
            $arr[] = $all[$i];
        }

        return $arr;
    }

    public function getCountriesByTest(Test $test): array
    {
        $q = $test->getQuestions();
        $qb = $this->createQueryBuilder('c');

        for ($i=0; $i < sizeof($q); $i++){
            $qb->andWhere("c.id = :q$i");
            $qb->setParameter("q$i", $q[$i]['id']);
        }
        return $qb->getQuery()->execute();
    }

//    /**
//     * @return Country[] Returns an array of Country objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Country
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
