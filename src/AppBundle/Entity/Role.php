<?php
/**
 * Created by PhpStorm.
 * User: Macola
 * Date: 29.9.2017.
 * Time: 16:35
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $name;
    /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @Assert\NotBlank()
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $person;
    /**
     * @ORM\ManyToOne(targetEntity="Movie")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $movie;



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param mixed $person
     */
    public function setPerson(Person $person)
    {
        $this->person = $person;
    }

    /**
     * @return mixed
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * @param mixed $movie
     */
    public function setMovie(Movie $movie)
    {
        $this->movie = $movie;
    }
}