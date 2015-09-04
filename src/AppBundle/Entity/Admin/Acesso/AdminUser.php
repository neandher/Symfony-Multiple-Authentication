<?php

namespace AppBundle\Entity\Admin\Acesso;

use AppBundle\Entity\SecureAreaInterface;
use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AdminUser
 *
 * @ORM\Table(name="admin_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Admin\Acesso\AdminUserRepository")
 */
class AdminUser implements SecureAreaInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="255")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="255")
     */
    private $lastName;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", cascade={"persist","remove"}, inversedBy="adminUser")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $user;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return AdminUser
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return AdminUser
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return AdminUser
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getCompleteName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
}
