<?php

namespace App\MessageHandler;

use App\Entity\Blog\Post;
use App\Message\NewBlogPostMessage;
use App\Repository\BlogPostRepository;
use App\Repository\UserRepository;
use DateTime;
use Exception;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Security;

final class NewBlogPostMessageHandler implements MessageHandlerInterface {
    private BlogPostRepository $postRepository;
    private Security $security;
    private UserRepository $userRepository;

    /**
     * Constructor.
     *
     * @param BlogPostRepository $repository Blog post repository.
     * @param Security $security Security.
     * @param UserRepository $userRepository User repository.
     */
    public function __construct(BlogPostRepository $repository, Security $security, UserRepository $userRepository) {
        $this->postRepository = $repository;
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    /**
     * Create a new blog post.
     *
     * @param NewBlogPostMessage $message The message to handle.
     * @return boolean Return true if the blog post was created.
     * @throws Exception
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
