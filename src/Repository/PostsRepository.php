<?php

namespace App\Repository;

use App\Entity\Posts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Posts>
 */
class PostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posts::class);
    }

    public function findPaginated(int $page = 1, int $limit = 2): array
    {
        $offset = ($page - 1) * $limit;
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $query = $qb->getQuery();

        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $pageCount = ceil($totalItems / $limit);

        return [
            'data' => $paginator,
            'currentPage' => $page,
            'totalItems' => $totalItems,
            'pageCount' => $pageCount,
            'limit' => $limit
        ];
    }

    public function findUserCreatedPosts(int $userId): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('p.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function findUserLikedPosts(int $userId): array
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.likes', 'l')
            ->where('l.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('p.createdAt', 'DESC');

        return $qb->getQuery()->getResult();

    }

    public function findUserFavoritePosts(int $userId): array
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.favorites', 'f')
            ->where('f.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('p.createdAt', 'DESC');

        return $qb->getQuery()->getResult();

    }
//    /**
//     * @return Posts[] Returns an array of Posts objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Posts
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
