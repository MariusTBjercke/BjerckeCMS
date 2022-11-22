<?php

namespace App\Controller\Games;

use App\Entity\Games\GameMap;
use App\Form\Type\Games\NewMapType;
use App\Repository\GameMapRepository;
use App\Service\Games\Map\AddMapMarkerService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Games.
 *
 * @Route("/games")
 */
class GamesController extends AbstractController {
    private LoggerInterface $logger;

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger Logger.
     */
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    /**
     * Games index route.
     *
     * @Route("/", name="games_index", methods={"GET"})
     */
    public function index(): Response {
        return $this->render('pages/games/games.html.twig', []);
    }

    /**
     * Game maps route.
     *
     * @Route("/maps", name="games_maps", methods={"GET"})
     * @return Response
     */
    public function gameMaps(): Response {
        return $this->render('pages/games/maps.html.twig', []);
    }

    /**
     * New game map route.
     *
     * @Route("/maps/new", name="games_maps_new", methods={"GET", "POST"})
     * @param Request $request Request.
     * @param SluggerInterface $slugger Slugger.
     * @param GameMapRepository $mapRepository Map repository.
     * @return Response
     */
    public function newGameMap(
        Request $request,
        SluggerInterface $slugger,
        GameMapRepository $mapRepository,
    ): Response {
        $form = $this->createForm(NewMapType::class, null, [
            'attr' => [
                'data-games-target' => 'form',
            ],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

            if ($image instanceof UploadedFile) {
                if ($image->getError() !== UPLOAD_ERR_OK) {
                    return new JsonResponse(
                        [
                            'error' => 'Error uploading file.',
                        ],
                        Response::HTTP_BAD_REQUEST,
                    );
                }
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

            // Create new game map entity
            $gameMap = new GameMap();
            $gameMap->setName($form->get('name')->getData());
            $gameMap->setDescription($form->get('description')->getData());
            $gameMap->setImage($newFilename);

            $mapRepository->add($gameMap, true);

            return new JsonResponse([
                'success' => true,
                'redirect' => $this->generateUrl('games_maps_edit', [
                    'id' => $gameMap->getId(),
                ]),
            ]);
        }

        return $this->render('pages/games/new_map.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Map editor route.
     *
     * @Route("/maps/edit/{id}", name="games_maps_edit", methods={"GET", "POST"})
     * @param Request $request Request.
     * @param GameMapRepository $mapRepository Game map repository.
     * @param AddMapMarkerService $addMapMarkerService Add map marker service.
     * @param integer|null $id Map ID.
     * @return Response
     */
    public function editMap(
        Request $request,
        GameMapRepository $mapRepository,
        AddMapMarkerService $addMapMarkerService,
        ?int $id = null,
    ): Response {
        if ($id === null) {
            return $this->redirectToRoute('games_index');
        }

        $map = $mapRepository->findByIdOrThrow($id);

        if ($request->isXmlHttpRequest()) {
            $addMapMarkerService->addMapMarker($request);

            return new JsonResponse([
                'success' => true,
            ]);
        }

        return $this->render('pages/games/_map_editor.html.twig', [
            'id' => $id,
            'map' => $map,
        ]);
    }
}
