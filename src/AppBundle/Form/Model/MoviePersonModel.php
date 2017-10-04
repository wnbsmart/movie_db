<?php
/**
 * Created by PhpStorm.
 * User: Macola
 * Date: 3.10.2017.
 * Time: 17:55
 */

namespace AppBundle\Form\Model;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class MoviePersonModel
{
    //Movie columns:
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $movie_name;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $movie_year;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $movie_description;

    //Role columns:
    /**
     * @ORM\Column(type="string")
     */
    protected $role_name;

    /**
     * @ORM\Column(type="integer")
     */
    protected $person_id;

    //Person column:
    /**
     * @ORM\Column(type="string")
     */
    protected $person_name;

    /**
     * @return mixed
     */
    public function getMovieName()
    {
        return $this->movie_name;
    }

    /**
     * @param mixed $movie_name
     */
    public function setMovieName($movie_name)
    {
        $this->movie_name = $movie_name;
    }

    /**
     * @return mixed
     */
    public function getMovieYear()
    {
        return $this->movie_year;
    }

    /**
     * @param mixed $movie_year
     */
    public function setMovieYear($movie_year)
    {
        $this->movie_year = $movie_year;
    }

    /**
     * @return mixed
     */
    public function getMovieDescription()
    {
        return $this->movie_description;
    }

    /**
     * @param mixed $movie_description
     */
    public function setMovieDescription($movie_description)
    {
        $this->movie_description = $movie_description;
    }

    /**
     * @return mixed
     */
    public function getPersonName()
    {
        return $this->person_name;
    }

    /**
     * @param mixed $person_name
     */
    public function setPersonName($person_name)
    {
        $this->person_name = $person_name;
    }

    /**
     * @return mixed
     */
    public function getRoleName()
    {
        return $this->role_name;
    }

    /**
     * @param mixed $role_name
     */
    public function setRoleName($role_name)
    {
        $this->role_name = $role_name;
    }

    /**
     * @return mixed
     */
    public function getPersonId()
    {
        return $this->person_id;
    }

    /**
     * @param mixed $person_id
     */
    public function setPersonId($person_id)
    {
        $this->person_id = $person_id;
    }

}