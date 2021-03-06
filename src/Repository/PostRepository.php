<?php

namespace App\Repository;

use App\Dictionary\Constants;
use App\DTO\PostFilterRequest;
use App\Entity\Post;
use App\Entity\User;
use App\Filters\PostFilter;
use App\Repository\Traits\Filterable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
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
    use Filterable;

    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var PostFilter
     */
    private $filter;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $em,
        PostFilter $filter
    ) {
        parent::__construct($registry, Post::class);
        $this->em = $em;
        $this->filter = $filter;
    }

    /**
     * @param int $userId
     *
     * @return Query
     */
    public function findUserPostOrderedByNewest(int $userId): Query
    {
        $query = $this->createQueryBuilder('p');

        return $query
            ->innerJoin('p.user', 'u')
            ->addSelect('u')
            ->andWhere('p.user_id = :user_id')
            ->setParameter('user_id', $userId)
            ->orderBy('p.createdAt', 'ASC')
            ->setMaxResults(Constants::POST_PER_PAGE)
            ->getQuery();
    }

    /**
     * @return Criteria
     */
    public static function createNonDeletedCriteria(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('isDeleted', false))
            ->orderBy(['createdAt' => 'DESC']);
    }

    /**
     * @return Query
     */
    public function getPostListOrderedByNewest(): Query
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('p.createdAt', 'ASC')
            ->getQuery();
    }

    /**
     * @param QueryBuilder|null $qb
     *
     * @return QueryBuilder
     */
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
    public function getFilteredPostList(IRequestDto $dtoPost): Query
    {
        /**
         * Test Doctrine filters
         * It's global solution for application
         *
         * @see links with descripyions
         * https://devacademy.ru/article/povyishenie-bezopasnosti-i-uproschenie-razrabotki-v-symfony2-pri-pomoschi-annotatsij-i-filtrov-doktrinyi/
         * http://blog.michaelperrin.fr/2014/12/05/doctrine-filters/
         */
        //$filter = $this->em->getFilters()->enable("test_global_filter");
        /** Bind params by hand to get them in addFilterConstraint() */
        /*
        $filter->setParameter('status', Post::getStatusByName($dtoPost->getStatus()));
        $filter->setParameter('dateFrom', $dtoPost->getDateFrom());
        $filter->setParameter('dateTo', $dtoPost->getDateTo());
        */

        // Another filtering approach
        /** @var  QueryBuilder $qBuilder */
        $qBuilder = $this->addFilters(
            $this->createQueryBuilder($alias = 'p'),
            $this->filter,
            $dtoPost, // i.e. validated filter fields
            $alias
        );

        return $qBuilder
            ->andWhere('p.status <> :draft') // admin has no access to user' draft
            ->setParameter('draft', Post::getStatusByName(Post::STATUS_DRAFT))
            ->orderBy('p.createdAt', 'ASC')
            ->getQuery();
    }

    /**
     * @param int $limit
     * @param int $postStatus
     *
     * @return array
     */
    public function getRandomPosts(int $limit = 0, int $postStatus = 0): array
    {
        $query = $this->createQueryBuilder($alias = 'post')
            ->innerJoin('post.user', 'post_user')
            ->addSelect('post_user');

        if ($postStatus) {
            $query->andWhere('post.status = ' . $postStatus);
        }
        if ($limit) {
            $query->setMaxResults($limit);
        }

        return $query->getQuery()->getResult();
    }
}
