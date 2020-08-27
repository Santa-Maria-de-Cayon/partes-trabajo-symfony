<?php

namespace App\Entity;

use App\Repository\PartRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PartRepository::class)
 */
class Part
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $data;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $workid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $user;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $time;

    /**
     * @ORM\Column(type="text",  nullable=true)
     */
    private $do;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $material;


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

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getDo(): ?string
    {
        return $this->do;
    }

    public function setDo(?string $do): self
    {
        $this->do = $do;

        return $this;
    }

    public function getWorkid()
    {
        return $this->workid;
    }

    public function setWorkid($workid): self
    {
        $this->workid = $workid;
        return $this;
    }

    public function getMaterial()
    {
        return $this->material;
    }


    public function setMaterial($material): self
    {
        $this->material = $material;
        return $this;
    }
}
