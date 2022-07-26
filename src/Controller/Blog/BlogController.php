<?php

namespace App\Controller\Blog;

use App\CQRS\Query\BlogPostsQuery;
use App\Form\Type\Blog\BlogPostType;
use App\Message\NewBlogPostMessage;
use App\Request\NewBlogPostRequest;
use App\Shared\AjaxFormErrorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController {
    private MessageBusInterface $bus;
    private RequestStack $requestStack;

    public function __construct(MessageBusInterface $bus, RequestStack $requestStack) {
        $this->bus = $bus;
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("/", name="blog_index", methods={"GET"})
     */
    public function index(BlogPostsQuery $blogPostsQuery): Response {
        $posts = $blogPostsQuery();

        $newBlogPostRequest = new NewBlogPostRequest();
        $form = $this->createForm(BlogPostType::class, $newBlogPostRequest, [
            'attr' => [
                'data-blog-target' => 'form',
            ],
        ]);

        return $this->render('pages/blog/index.html.twig', [
            'form' => $form->createView(),
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/new", name="blog_post_new", methods={"POST"})
     */
    public function newPost(Request $request): JsonResponse {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['serverError' => 'Not an AJAX request.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        dump($this->getUser());

        if ($this->getUser() === null) {
            return new JsonResponse(
                [
                    'error' => 'You must be logged in to create a blog post.',
                ],
                Response::HTTP_UNAUTHORIZED,
            );
        }

        $newBlogPostRequest = new NewBlogPostRequest();

        // Add data attribute to form so that we can target it in the JS.
        $form = $this->createForm(BlogPostType::class, $newBlogPostRequest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = new NewBlogPostMessage($newBlogPostRequest->title, $newBlogPostRequest->content);
            $envelope = $this->bus->dispatch($message);
            $handledStamp = $envelope->last(HandledStamp::class);
            $result = $handledStamp->getResult();
        }

        if (!$form->isValid()) {
            $errorHandler = new AjaxFormErrorHandler($form);
            $errors = $errorHandler->getErrors();
        }

        $result = $result ?? false;

        return new JsonResponse(
            [
                'success' => $result,
                'result' => $result
                    ? [
                        'title' => $newBlogPostRequest->title,
                        'content' => $newBlogPostRequest->content,
                        'author' => $this->getUser()->getUsername(),
                    ]
                    : [
                        'errors' => $errors ?? [],
                    ],
            ],
            Response::HTTP_CREATED,
        );
    }
}
