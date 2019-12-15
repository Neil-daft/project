<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project", mappedBy="user")
     */
    private $project;

//    /**
//     * @ORM\Column(type="string", length=180, unique=true)
//     */
//    pro $username;

//    /**
//     * @ORM\Column(type="json")
//     */
//    protected $roles = [];

//    /**
//     * @var string The hashed password
//     * @ORM\Column(type="string")
//     */
//    protected $password;

    public function __construct()
    {
        parent::__construct();
        $this->project = new ArrayCollection();
        // your own logic
    }

    /**
     * @return Collection|project[]
     */
    public function getProject(): Collection
    {
        return $this->project;
    }

    public function addProject(project $project): self
    {
        if (!$this->project->contains($project)) {
            $this->project[] = $project;
            $project->setUser($this);
        }

        return $this;
    }

    public function removeProject(project $project): self
    {
        if ($this->project->contains($project)) {
            $this->project->removeElement($project);
            // set the owning side to null (unless already changed)
            if ($project->getUser() === $this) {
                $project->setUser(null);
            }
        }

        return $this;
    }
//
//    /**
//     * @return int
//     */
//    public function getId()
//    {
//        return $this->id;
//    }
//
//    /**
//     * A visual identifier that represents this user.
//     *
//     * @see UserInterface
//     * @return string
//     */
//    public function getUsername()
//    {
//        return (string) $this->username;
//    }
//
//    /**
//     * @param  string $username
//     * @return \App\Entity\User
//     */
//    public function setUsername($username)
//    {
//        $this->username = $username;
//
//        return $this;
//    }
//
//    /**
//     * @see UserInterface
//     * @return array
//     */
//    public function getRoles()
//    {
//        $roles = $this->roles;
//        // guarantee every user at least has ROLE_USER
//        $roles[] = 'ROLE_USER';
//
//        return array_unique($roles);
//    }
//
//    /**
//     * @param array $roles
//     * @return \App\Entity\User
//     */
//    public function setRoles(array $roles)
//    {
//        $this->roles = $roles;
//
//        return $this;
//    }
//
//    /**
//     * @see UserInterface
//     * @return string
//     */
//    public function getPassword()
//    {
//        return (string) $this->password;
//    }
//
//    /**
//     * @param \App\Entity\string $password
//     * @return $this
//     */
//    public function setPassword($password)
//    {
//        $this->password = $password;
//
//        return $this;
//    }
//
//    /**
//     * @see UserInterface
//     */
//    public function getSalt()
//    {
//        // not needed when using the "bcrypt" algorithm in security.yaml
//    }
//
//    /**
//     * @see UserInterface
//     */
//    public function eraseCredentials()
//    {
//        // If you store any temporary, sensitive data on the user, clear it here
//        // $this->plainPassword = null;
//    }
}
