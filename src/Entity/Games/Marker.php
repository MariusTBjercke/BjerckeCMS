<?php

declare(strict_types=1);

namespace App\Entity\Games;

use App\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Marker entity.
 *
 * @ORM\Entity(repositoryClass=MarkerRepository::class)
 * @ORM\Table(name="markers")
 */
class Marker extends AbstractEntity {
    /**
     * Marker name.
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * Marker description.
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $description;

    /**
     * Marker image.
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $image;

    /**
     * Marker X position.
     *
     * @ORM\Column(type="string", length=255)
     */
    private int $x;

    /**
     * Marker Y position.
     *
     * @ORM\Column(type="string", length=255)
     */
    private int $y;

    /**
     * Game map.
     *
     * @ORM\ManyToOne(targetEntity=GameMap::class, inversedBy="markers")
     * @ORM\JoinColumn(nullable=false)
     */
    private GameMap $map;

    /**
     * Constructor.
     *
     * @param string $name Marker name.
     * @param string $description Marker description.
     * @param integer $x Marker X position.
     * @param integer $y Marker Y position.
     */
    public function __construct(string $name, string $description, int $x, int $y) {
        $this->name = $name;
        $this->description = $description;
        $this->x = $x;
        $this->y = $y;
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
     * Set name.
     *
     * @param string $name Name.
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param string $description Description.
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
     * Get X position.
     *
     * @return integer
     */
    public function getX(): int {
        return $this->x;
    }

    /**
     * Set X position.
     *
     * @param integer $x X position.
     */
    public function setX(int $x): void {
        $this->x = $x;
    }

    /**
     * Get Y position.
     *
     * @return integer
     */
    public function getY(): int {
        return $this->y;
    }

    /**
     * Y position.
     *
     * @param integer $y Y position.
     */
    public function setY(int $y): void {
        $this->y = $y;
    }

    /**
     * Get map.
     *
     * @return GameMap
     */
    public function getMap(): GameMap {
        return $this->map;
    }

    /**
     * Set map.
     *
     * @param GameMap|null $map Game map.
     */
    public function setMap(?GameMap $map): void {
        $this->map = $map;
    }
}
