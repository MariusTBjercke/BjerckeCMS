<?php

namespace App\CQRS\Query\Model;

use DateTime;

class BlogPostModel {
    public int $id;
    public string $title;
    public string $content;
    public string $author;
    public string $publishedAt;

    /**
     * Constructor.
     *
     * @param integer $id Id.
     * @param string $title Title.
     * @param string $content Content.
     * @param string $author Author.
     * @param DateTime $createdAt Published at.
     */
    public function __construct(int $id, string $title, string $content, string $author, DateTime $createdAt) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
        $this->publishedAt = $createdAt->format('Y-m-d H:i:s');
    }
}
