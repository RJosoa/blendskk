<?php

namespace App\Repository;

use App\Entity\Favorites;
use App\Entity\Posts;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Favorites>
 */
class FavoritesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favorites::class);
    }

    public function hasUserFavoritedPost(Posts $post, User $user): bool
    {
        $favorite = $this->findOneBy([
            'post' => $post,
            'user' => $user,
        ]);
        return $favorite !== null;
    }
}
