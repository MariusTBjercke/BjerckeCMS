<?php

namespace App\Entity\Blog;

use App\Entity\AbstractEntity;
use App\Entity\User;
use App\Repository\BlogPostRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

/**
 * Blog post entity.
 *
 * @ORM\Entity(repositoryClass=BlogPostRepository::class)
 * @Table(name="blog_posts")
 */
class Post extends AbstractEntity {
    /**
     * Post title.
     *
     * @ORM\Column(type="string", length=255)
     */
    private ?string $title;

    /**
     * Post content.
     *
     * @ORM\Column(type="text")
     */
    private ?string $content;

    /**
     * Is blog post published?
     *
     * @ORM\Column(type="boolean")
     */
    private bool $published;

    /**
     * Post published date.
     *
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $publishedAt;

    /**
     * Current state of the post.
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private array $currentPlace = [];

    /**
     * Post author.
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="blogPosts")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $author;

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string {
        return $this->content;
    }

    public function setContent(string $content): self {
        $this->content = $content;

        return $this;
    }

    public function isPublished(): ?bool {
        return $this->published;
    }

    public function setPublished(bool $published): self {
        $this->published = $published;

        return $this;
    }

    public function getPublishedAt(): ?DateTimeInterface {
        return $this->publishedAt;
    }

    public function setPublishedAt(DateTimeInterface $publishedAt): self {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getAuthor(): ?User {
        return $this->author;
    }

    public function setAuthor(User $author): self {
        $this->author = $author;

        return $this;
    }

    /**
     * @return array
     */
    public function getCurrentPlace(): array {
        return $this->currentPlace;
    }

    /**
     * @param array $currentPlace
     * @param array $context
     */
    public function setCurrentPlace(array $currentPlace, array $context = []): void {
        $this->currentPlace = $currentPlace;
    }
}
