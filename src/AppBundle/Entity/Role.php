<?php
/**
 * Created by PhpStorm.
 * User: Macola
 * Date: 29.9.2017.
 * Time: 16:35
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $name;
    /**
     * @ORM\Column(type="integer")
     */
    private $person_id;
    /**
     * @ORM\Column(type="integer")
     */
    private $movie_id;
}