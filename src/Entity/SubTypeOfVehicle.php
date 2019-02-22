<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubTypeOfVehicleRepository")
 */
class SubTypeOfVehicle
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeOfVehicle", inversedBy="subTypeOfVehicles")
     */
    private $typeOfVehicle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTypeOfVehicle(): ?TypeOfVehicle
    {
        return $this->typeOfVehicle;
    }

    public function setTypeOfVehicle(?TypeOfVehicle $typeOfVehicle): self
    {
        $this->typeOfVehicle = $typeOfVehicle;

        return $this;
    }
}
