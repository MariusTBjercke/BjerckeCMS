<?php

namespace App\MessageHandler;

use App\Entity\Blog\Post;
use App\Entity\User;
use App\Message\NewBlogPostMessage;
use App\Repository\BlogPostRepository;
use App\Repository\UserRepository;
use DateTime;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Security;

final class NewBlogPostMessageHandler implements MessageHandlerInterface {
    private BlogPostRepository $postRepository;
    private Security $security;
    private UserRepository $userRepository;

    public function __construct(BlogPostRepository $repository, Security $security, UserRepository $userRepository) {
        $this->postRepository = $repository;
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    /**
     * Create a new user.
     *
     * @param NewBlogPostMessage $message
     * @return bool Return true if the blog post was created.
     */
    public function __invoke(NewBlogPostMessage $message): bool {
        $user = $this->security->getUser();

        $user = $this->userRepository->findByIdOrThrow($user->getId());

        // Create blog post
        $post = (new Post())
            ->setTitle($message->getTitle())
            ->setContent($message->getContent())
            ->setAuthor($user)
            ->setPublished(true)
            ->setPublishedAt(new DateTime());

        $this->postRepository->add($post, true);

        return true;
    }
}
