<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\Type\Profile\ProfileImageUploadType;
use App\Repository\UserRepository;
use App\Service\Image\ImageOptimizer;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Profile controller.
 *
 * @Route("/profile")
 */
class ProfileController extends AbstractController {
    private ImageOptimizer $imageOptimizer;
    private UserRepository $userRepository;

    /**
     * Construct.
     *
     * @param ImageOptimizer $imageOptimizer Image optimizer.
     */
    public function __construct(ImageOptimizer $imageOptimizer, UserRepository $userRepository) {
        $this->imageOptimizer = $imageOptimizer;
        $this->userRepository = $userRepository;
    }

    /**
     * Profile index page.
     *
     * @Route("/", name="profile_index", methods={"GET"})
     */
    public function index(): Response {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->redirectToRoute('homepage');
        }

        $image = $user->getProfileImage();
        $imageSrc =
            $image !== null && trim($image) !== '' ? $this->getParameter('images_directory') . '/' . $image : null;

        $form = $this->createForm(ProfileImageUploadType::class, null, [
            'attr' => [
                'data-profile-target' => 'form',
                'class' => 'profile__form',
            ],
        ]);

        return $this->render('pages/profile/profile.html.twig', [
            'form' => $form->createView(),
            'profile_image' => [
                'src' => $imageSrc,
            ],
        ]);
    }

    /**
     * Profile image upload.
     *
     * @Route("/upload", name="profile_upload", methods={"POST"})
     * @param Request $request Request.
     * @param SluggerInterface $slugger Slugger.
     * @return JsonResponse
     */
    public function upload(Request $request, SluggerInterface $slugger): JsonResponse {
        $form = $this->createForm(ProfileImageUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

            if ($image instanceof UploadedFile) {
                if ($image->getError() !== UPLOAD_ERR_OK) {
                    return new JsonResponse(
                        ['error' => $image->getErrorMessage()],
                        Response::HTTP_INTERNAL_SERVER_ERROR,
                    );
                }

                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid('', true) . '.' . $image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('kernel.project_dir') . '/public' . $this->getParameter('images_directory'),
                        $newFilename,
                    );
                } catch (FileException $e) {
                    return new JsonResponse(
                        [
                            'success' => false,
                            'error' => 'Could not upload image.',
                            'exception' => $e->getMessage(),
                        ],
                        Response::HTTP_INTERNAL_SERVER_ERROR,
                    );
                }

                // Update user profile image.
                $user = $this->userRepository->findByIdOrThrow($this->getUser()->getId());
                $user->setProfileImage($newFilename);
                $this->userRepository->save();

                return new JsonResponse(
                    [
                        'success' => true,
                        'image_url' => isset($newFilename)
                            ? $this->getParameter('images_directory') . '/' . $newFilename
                            : null,
                    ],
                    Response::HTTP_CREATED,
                );
            }
        }

        return new JsonResponse(['success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Delete/unset profile picture.
     *
     * @Route("/remove", name="profile_remove_picture", methods={"GET"})
     * @param Request $request Request.
     * @throws Exception
     */
    public function removeProfilePicture(Request $request): RedirectResponse {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->redirectToRoute("homepage");
        }

        // Remove user profile image.
        $user = $this->userRepository->findByIdOrThrow($this->getUser()->getId());
        $user->setProfileImage(null);
        $this->userRepository->save();

        return $this->redirectToRoute("profile_index");
    }
}
