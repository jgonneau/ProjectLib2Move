<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeOfVehicleRepository")
 */
class TypeOfVehicle
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
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vehicle", mappedBy="typeOfVehicle")
     */
    private $vehicle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SubTypeOfVehicle", mappedBy="typeOfVehicle")
     */
    private $subTypeOfVehicles;

    public function __construct()
    {
        $this->vehicle = new ArrayCollection();
        $this->subTypeOfVehicles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Vehicle[]
     */
    public function getVehicle(): Collection
    {
        return $this->vehicle;
    }

    public function addVehicle(Vehicle $vehicle): self
    {
        if (!$this->vehicle->contains($vehicle)) {
            $this->vehicle[] = $vehicle;
            $vehicle->setTypeOfVehicle($this);
        }

        return $this;
    }

    public function removeVehicle(Vehicle $vehicle): self
    {
        if ($this->vehicle->contains($vehicle)) {
            $this->vehicle->removeElement($vehicle);
            // set the owning side to null (unless already changed)
            if ($vehicle->getTypeOfVehicle() === $this) {
                $vehicle->setTypeOfVehicle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SubTypeOfVehicle[]
     */
    public function getSubTypeOfVehicles(): Collection
    {
        return $this->subTypeOfVehicles;
    }

    public function addSubTypeOfVehicle(SubTypeOfVehicle $subTypeOfVehicle): self
    {
        if (!$this->subTypeOfVehicles->contains($subTypeOfVehicle)) {
            $this->subTypeOfVehicles[] = $subTypeOfVehicle;
            $subTypeOfVehicle->setTypeOfVehicle($this);
        }

        return $this;
    }

    public function removeSubTypeOfVehicle(SubTypeOfVehicle $subTypeOfVehicle): self
    {
        if ($this->subTypeOfVehicles->contains($subTypeOfVehicle)) {
            $this->subTypeOfVehicles->removeElement($subTypeOfVehicle);
            // set the owning side to null (unless already changed)
            if ($subTypeOfVehicle->getTypeOfVehicle() === $this) {
                $subTypeOfVehicle->setTypeOfVehicle(null);
            }
        }

        return $this;
    }
}
