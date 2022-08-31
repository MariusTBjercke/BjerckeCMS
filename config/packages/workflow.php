<?php

use App\Entity\Blog\Post;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $config) {
    $blogPost = $config->workflows()->workflows('blog_post');

    $blogPost
        ->type('workflow')
        ->supports([Post::class])
        ->initialMarking('draft');

    $blogPost->auditTrail()->enabled(true);
    $blogPost
        ->markingStore()
        ->type('method')
        ->property('currentPlace');

    $blogPost->place()->name('draft');
    $blogPost->place()->name('reviewed');
    $blogPost->place()->name('rejected');
    $blogPost->place()->name('published');

    $blogPost
        ->transition()
        ->name('review_reject')
        ->from('draft')
        ->to(['reviewed', 'rejected']);

    $blogPost
        ->transition()
        ->name('review_publish')
        ->from('draft')
        ->to(['reviewed', 'published']);
};
