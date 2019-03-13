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
     * @ORM\OneToMany(targetEntity="App\Entity\UserRole", mappedBy="role")
     */
    private $userRole;

    /**
     * @ORM\Column(type="integer")
     */
    private $levelRole;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $defaultPathRedirection;



    /**
     * Role constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->accessRoles = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->userRole = new ArrayCollection();
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

    /**
     * @return Collection|UserRole[]
     */
    public function getUserRole(): Collection
    {
        return $this->userRole;
    }

    public function addUserRole(UserRole $userRole): self
    {
        if (!$this->userRole->contains($userRole)) {
            $this->userRole[] = $userRole;
            $userRole->setRole($this);
        }

        return $this;
    }

    public function removeUserRole(UserRole $userRole): self
    {
        if ($this->userRole->contains($userRole)) {
            $this->userRole->removeElement($userRole);
            // set the owning side to null (unless already changed)
            if ($userRole->getRole() === $this) {
                $userRole->setRole(null);
            }
        }

        return $this;
    }

    public function getLevelRole(): ?int
    {
        return $this->levelRole;
    }

    public function setLevelRole(int $levelRole): self
    {
        $this->levelRole = $levelRole;

        return $this;
    }

    public function getDefaultPathRedirection(): ?string
    {
        return $this->defaultPathRedirection;
    }

    public function setDefaultPathRedirection(string $defaultPathRedirection): self
    {
        $this->defaultPathRedirection = $defaultPathRedirection;

        return $this;
    }

}
