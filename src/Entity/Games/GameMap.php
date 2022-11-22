<?php

namespace App\Entity\Games;

use App\Entity\AbstractEntity;
use App\Repository\GameMapRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

/**
 * Game Map entity.
 *
 * @ORM\Entity(repositoryClass=GameMapRepository::class)
 * @Table(name="game_maps")
 */
class GameMap extends AbstractEntity {
    /**
     * Name.
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * Description.
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $description;

    /**
     * Image path.
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $image;

    /**
     * Map marker data.
     *
     * @ORM\OneToMany(targetEntity=Marker::class, mappedBy="map", cascade={"persist"})
     */
    private Collection $markers;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->markers = new ArrayCollection();
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Set map name.
     *
     * @param string $name Map name.
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * Get map description.
     *
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * Set map description.
     *
     * @param string $description Map description.
     */
    public function setDescription(string $description): void {
        $this->description = $description;
    }

    /**
     * Get image.
     *
     * @return string
     */
    public function getImage(): string {
        return $this->image;
    }

    /**
     * Set image.
     *
     * @param string $image Image.
     */
    public function setImage(string $image): void {
        $this->image = $image;
    }

    /**
     * Get markers.
     *
     * @return Collection
     */
    public function getMarkers(): Collection {
        return $this->markers;
    }

    /**
     * Set marker.
     *
     * @param Marker $marker Marker.
     * @return void
     */
    public function setMarker(Marker $marker): void {
        $this->markers[] = $marker;
    }

    /**
     * Add marker.
     *
     * @param Marker $marker Marker.
     * @return GameMap
     */
    public function addMarker(Marker $marker): GameMap {
        if (!$this->markers->contains($marker)) {
            $this->markers[] = $marker;
            $marker->setMap($this);
        }

        return $this;
    }

    /**
     * Remove marker.
     *
     * @param Marker $marker Marker.
     * @return void
     */
    public function removeMarker(Marker $marker): void {
        if ($this->markers->contains($marker)) {
            $this->markers->removeElement($marker);

            if ($marker->getMap() === $this) {
                $marker->setMap(null);
            }
        }
    }
}
