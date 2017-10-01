<?php
/**
 * Created by PhpStorm.
 * User: Macola
 * Date: 30.9.2017.
 * Time: 13:18
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CMSController extends Controller
{
    /**
     * @Route("/cms", name="cms")
     */
    public function indexAction()
    {
        return $this->render('cms/index.html.twig');
    }
}