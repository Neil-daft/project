<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project
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
    private $owner;

    /**
     * @ORM\Column(type="date")
     */
    private $firstCreated;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /** @return int|null */
    public function getId()
    {
        return $this->id;
    }

    /** @return string|null */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @var string $owner
     * @return \App\Entity\Project
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getFirstCreated()
    {
        return $this->firstCreated;
    }

    /**
     * @param \DateTimeInterface $firstCreated
     * @return \App\Entity\Project
     */
    public function setFirstCreated(\DateTimeInterface $firstCreated)
    {
        $this->firstCreated = $firstCreated;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return \App\Entity\Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $value
     * @return \App\Entity\Project
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return \App\Entity\Project
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
}
