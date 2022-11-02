<?php

namespace App\Controller\Blog;

use App\CQRS\Query\BlogPostsQuery;
use App\Form\Type\Blog\BlogPostType;
use App\Message\NewBlogPostMessage;
use App\Repository\BlogPostRepository;
use App\Request\NewBlogPostRequest;
use App\Shared\AjaxFormErrorHandler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController {
    private MessageBusInterface $bus;
    private RequestStack $requestStack;
    private LoggerInterface $logger;


    public function __construct(MessageBusInterface $bus, RequestStack $requestStack, LoggerInterface $logger) {
        $this->bus = $bus;
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }

    /**
     * @Route("/", name="blog_index", methods={"GET"})
     */
    public function index(BlogPostsQuery $blogPostsQuery, BlogPostRepository $postRepository): Response {
        $posts = $blogPostsQuery();

        $newBlogPostRequest = new NewBlogPostRequest();
        $form = $this->createForm(BlogPostType::class, $newBlogPostRequest, [
            'attr' => [
                'data-blog-target' => 'form',
            ],
        ]);

        return $this->render('pages/blog/blog.html.twig', [
            'form' => $form->createView(),
            'posts' => $posts,
        ]);
    }

    /**
     * New blog post.
     *
     * @Route("/post/new", name="blog_post_new", methods={"POST"})
     * @param Request $request Request.
     * @return JsonResponse
     */
    public function newPost(Request $request): JsonResponse {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['serverError' => 'Not an AJAX request.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

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
                        'date' => new \DateTime('now'),
                    ]
                    : [
                        'errors' => $errors ?? [],
                    ],
            ],
            Response::HTTP_CREATED,
        );
    }

    /**
     * TinyMCE image upload.
     *
     * @Route("/upload", name="blog_upload", methods={"POST"})
     * @param Request $request Request.
     * @param SluggerInterface $slugger Slugger.
     * @return JsonResponse
     */
    public function upload(Request $request, SluggerInterface $slugger): Response {
        $file = $request->files->get('file');

        if ($file instanceof UploadedFile) {
            if ($file->getError() !== UPLOAD_ERR_OK) {
                return new JsonResponse(['error' => $file->getErrorMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid('', true) . '.' . $file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('kernel.project_dir') . '/public' . $this->getParameter('images_directory'),
                    $newFilename,
                );
            } catch (FileException $e) {
                return new JsonResponse(
                    [
                        'error' => 'An exception occurred while trying to upload the file.',
                        'exception' => $e->getMessage(),
                    ],
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                );
            }

            return new JsonResponse(
                ['location' => $this->getParameter('images_directory') . '/' . $newFilename],
                Response::HTTP_CREATED,
            );
        }

        return new JsonResponse(['error' => 'No file uploaded.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
