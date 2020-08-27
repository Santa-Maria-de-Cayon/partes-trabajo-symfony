<?php

namespace App\Entity;

use App\Repository\WorkersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WorkersRepository::class)
 */
class Workers
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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $c_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $c_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $createdfor;


    public function getCreatedfor(): ?string
    {
        return $this->createdfor;
    }

    public function setCreatedfor(?string $createdfor): self
    {
        $this->createdfor = $createdfor;
        return $this;
    }

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCId(): ?string
    {
        return $this->c_id;
    }

    public function setCId(string $c_id): self
    {
        $this->c_id = $c_id;

        return $this;
    }

    public function getCName(): ?string
    {
        return $this->c_name;
    }

    public function setCName(string $c_name): self
    {
        $this->c_name = $c_name;

        return $this;
    }
}
