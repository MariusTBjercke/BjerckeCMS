<?php

namespace App\CQRS\Query;

use App\Entity\Blog\Post;
use Doctrine\ORM\EntityManagerInterface;

class BlogPostsQuery {
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * Return all blog posts.
     *
     * @return array
     */
    public function __invoke(): array {
        $query = $this->entityManager->createQueryBuilder();

        $select = <<<EOD
            NEW App\CQRS\Query\Model\BlogPostModel(
                b.id,
                b.title,
                b.content,
                a.username,
                b.publishedAt
            )
        EOD;

        $query
            ->select($select)
            ->from(Post::class, 'b')
            ->join('b.author', 'a')
            ->orderBy('b.publishedAt', 'DESC');

        return $query->getQuery()->getResult();
    }
}
