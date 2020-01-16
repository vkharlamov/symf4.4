<?php

namespace App\Repository;

use App\DTO\PostFilterRequest;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\DTO\IRequestDto;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param int $userId
     *
     * @return \Doctrine\ORM\Query
     */
    public function findUserPostOrderedByNewest(int $userId): Query
    {
        $query = $this->createQueryBuilder('p');

        return $query
            ->innerJoin('p.user', 'u')
            ->addSelect('u')
            //   Join::WITH,
            //    $query->expr()->eq('p.user_id', 'u.id')
            ->andWhere('p.user_id = :user_id')
            ->setParameter('user_id', $userId)
            ->orderBy('p.createdAt', 'ASC')
            ->setMaxResults(Post::LIMIT_PER_PAGE)
            ->getQuery();
    }

    public static function createNonDeletedCriteria(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('isDeleted', false))
            ->orderBy(['createdAt' => 'DESC']);
    }

    public function getPostListOrderedByNewest(): Query
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('p.createdAt', 'ASC')
            ->getQuery();
    }

    private function addIsPublishedQueryBuilder(QueryBuilder $qb = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($qb);
//            ->andWhere('p.status = :post_status')
//            ->setParameter('post_status', Post::STATUS_PUBLISHED_KEY);
    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null): QueryBuilder
    {
        return $qb ?: $this->createQueryBuilder('p');
    }

    public function getStatusesForAdmin(): array
    {
        return array_flip([
            Post::STATUS_DRAFT_KEY => Post::STATUS_DRAFT,
            Post::STATUS_DECLINED_KEY => Post::STATUS_DECLINED,
            Post::STATUS_PUBLISHED_KEY => Post::STATUS_PUBLISHED,
        ]);
    }

    /**
     * @param IRequestDto $dtoPost
     *
     * @return Query
     */
    public function getFilteredPostList(IRequestDto $dtoPost) {
        //dd($request);
        // @TODO ADD filter params
        return $this->getOrCreateQueryBuilder()
            ->orderBy('p.createdAt', 'ASC')
            ->getQuery();
    }
}
