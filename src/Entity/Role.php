<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
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
    private $nomRole;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descriptionRole;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $modifiedBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $createdBy;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AccessRole", mappedBy="role")
     */
    private $accessRoles;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserRole", inversedBy="role")
     */
    private $userRole;

    /**
     * Role constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->accessRoles = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomRole(): ?string
    {
        return $this->nomRole;
    }

    public function setNomRole(string $nomRole): self
    {
        $this->nomRole = $nomRole;

        return $this;
    }

    public function getDescriptionRole(): ?string
    {
        return $this->descriptionRole;
    }

    public function setDescriptionRole(?string $descriptionRole): self
    {
        $this->descriptionRole = $descriptionRole;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedBy(): ?string
    {
        return $this->modifiedBy;
    }

    public function setModifiedBy(?string $modifiedBy): self
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?\DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection|AccessRole[]
     */
    public function getAccessRoles(): Collection
    {
        return $this->accessRoles;
    }

    public function addAccessRole(AccessRole $accessRole): self
    {
        if (!$this->accessRoles->contains($accessRole)) {
            $this->accessRoles[] = $accessRole;
            $accessRole->addRole($this);
        }

        return $this;
    }

    public function removeAccessRole(AccessRole $accessRole): self
    {
        if ($this->accessRoles->contains($accessRole)) {
            $this->accessRoles->removeElement($accessRole);
            $accessRole->removeRole($this);
        }

        return $this;
    }

    public function getUserRole(): ?UserRole
    {
        return $this->userRole;
    }

    public function setUserRole(?UserRole $userRole): self
    {
        $this->userRole = $userRole;

        return $this;
    }
}
