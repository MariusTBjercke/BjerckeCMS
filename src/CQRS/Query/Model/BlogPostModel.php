<?php

namespace App\CQRS\Query\Model;

use DateTime;

class BlogPostModel {
    public int $id;
    public string $title;
    public string $content;
    public string $author;
    public DateTime $createdAt;

    public function __construct(int $id, string $title, string $content, string $author, DateTime $createdAt) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
        $this->createdAt = $createdAt;
    }
}
