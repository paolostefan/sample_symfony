<?php

namespace App\Repository;

use App\Entity\Poi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Poi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Poi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Poi[]    findAll()
 * @method Poi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PoiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Poi::class);
    }

    public function search(string $searchQuery, $limit = 25)
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
          ->where($qb->expr()->like('p.title', ':val'))
          ->orWhere($qb->expr()->like('p.address', ':val'))
          ->orWhere($qb->expr()->like('p.city', ':val'))
          ->orWhere($qb->expr()->like('p.province', ':val'))
          ->orWhere($qb->expr()->like('p.region', ':val'))
          ->setParameter('val', '%'.$searchQuery.'%')
          ->setMaxResults($limit)
          ->getQuery()
          ->getResult();

    }

    // /**
    //  * @return Poi[] Returns an array of Poi objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Poi
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    /**
     * @param Request $request
     * @return array
     * @throws NonUniqueResultException|NoResultException
     */
    public function getDataTablesPage(Request $request): array
    {
        $start = $request->get("start", 0);
        $limit = $request->get("length", 10);
        $qb = $this->createQueryBuilder('p');
        $data = $qb
          ->select(
            [
              'p.id',
              'p.title',
              'p.city',
              'p.address',
              'p.zip',
              'p.province',
              'p.region',
              'p.country',
            ]
          )
          ->setFirstResult($start)
          ->setMaxResults($limit)
          ->getQuery()
          ->getArrayResult();
        $qb_count = $this->createQueryBuilder('p');
        $total = $qb_count->select(['count(p)'])->getQuery()->getSingleScalarResult();

        return [$data, $total, $total];
    }

}
