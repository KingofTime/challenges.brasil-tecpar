<?php

namespace App\Repository;

use App\Entity\Hash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hash>
 *
 * @method Hash|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hash|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hash[]    findAll()
 * @method Hash[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HashRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hash::class);
    }

    public function paginate(array $filter, int $perPage, int $page): array
    {
        $offset = ($page - 1) * $perPage;
        $criteria = Criteria::create();

        if(array_key_exists('amountTries', $filter)) {
            $criteria->where(Criteria::expr()->lte('amountTries', $filter['amountTries']));
        }

        $criteria->setMaxResults($perPage)
            ->setFirstResult($offset);

        return $this->matching($criteria)->toArray();
    }

    public function add(Hash $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Hash $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Hash[] Returns an array of Hash objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Hash
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
