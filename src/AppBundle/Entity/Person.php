<?php
/**
 * Created by PhpStorm.
 * User: Macola
 * Date: 29.9.2017.
 * Time: 16:16
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="person")
 */
class Person
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
     * @ORM\Column(type="date")
     */
    private $dateOfBirth;
    /**
     * @ORM\Column(type="string")
     */
    private $image_path;
}