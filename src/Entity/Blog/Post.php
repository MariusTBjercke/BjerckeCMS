<?php

namespace App\Entity\Blog;

use App\Entity\AbstractEntity;
use App\Entity\User;
use App\Repository\BlogPostRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

/**
 * @ORM\Entity(repositoryClass=BlogPostRepository::class)
 * @Table(name="blog_posts")
 */
class Post extends AbstractEntity {
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $title;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $published;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $publishedAt;

    /**
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
}
