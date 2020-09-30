<?php

namespace App\Entity;

use App\Repository\PoiRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PoiRepository::class)
 */
class Poi
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="geography", options={"geometry_type"="POINT"})
     */
    private $coords;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $zip;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $province;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity=PoiCategory::class, inversedBy="points")
     */
    private $category;

    /**
     * @var int SRID usato per le coordinate ($coords)
     */
    private ?int $srid;

    /**
     * @var float longitudine di $coords
     */
    private ?float $lon;

    /**
     * @var float latitudine di $coords
     */
    private ?float $lat;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCoords()
    {
        return $this->coords;
    }

    public function setCoords($coords): self
    {
        $this->coords = $coords;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(?string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(?string $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCategory(): ?PoiCategory
    {
        return $this->category;
    }

    public function setCategory(?PoiCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Popola i campi srid, lat e lon a partire dalle coordinate lette da db,
     * che sono una stringa del tipo 'SRID=4326;POINT(0.0 0.0)'
     */
    private function parseCoords(){
        sscanf($this->coords,"SRID=%d;POINT(%f %f)", $this->srid, $this->lon, $this->lat);
    }

    /**
     * @return float
     */
    public function getLon(): float
    {
        $this->parseCoords();
        return $this->lon;
    }

    /**
     * @return float
     */
    public function getLat(): float
    {
        $this->parseCoords();
        return $this->lat;
    }

    /**
     * @return int
     */
    public function getSrid(): int
    {
        $this->parseCoords();
        return $this->srid;
    }
}
