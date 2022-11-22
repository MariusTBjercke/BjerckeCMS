<?php

declare(strict_types=1);

namespace App\Service\Games\Map;

use App\Entity\Games\Marker;
use App\Repository\GameMapRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AddMapMarkerService {
    private GameMapRepository $gameMapRepository;
    private EntityManagerInterface $entityManager;

    /**
     * Constructor.
     *
     * @param GameMapRepository $gameMapRepository Game map repository.
     */
    public function __construct(GameMapRepository $gameMapRepository, EntityManagerInterface $entityManager) {
        $this->gameMapRepository = $gameMapRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Add map marker.
     *
     * @param Request $request Request.
     * @return void
     */
    public function addMapMarker(Request $request): void {
        $map = $this->gameMapRepository->findByIdOrThrow((string) $request->get('map_id'));

        $marker = new Marker(
            $request->get('name'),
            $request->get('description'),
            (int) $request->get('x'),
            (int) $request->get('y'),
        );

        $map->addMarker($marker);

        $this->gameMapRepository->save($map);
    }
}
