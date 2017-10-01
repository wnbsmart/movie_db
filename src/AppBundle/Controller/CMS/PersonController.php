<?php
/**
 * Created by PhpStorm.
 * User: Macola
 * Date: 30.9.2017.
 * Time: 15:05
 */

namespace AppBundle\Controller\CMS;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PersonController extends Controller
{
    /**
     * @Route("/cms/person/create", name="cms_create_person")
     */
    public function addPersonAction(Request $request)
    {
        return $this->render('cms/person/create.html.twig');
    }
    /**
     * @Route("/cms/person/edit/{id}", name="cms_edit_person")
     */
    public function editPersonAction($id, Request $request)
    {
        $person = $this->getDoctrine()
            ->getRepository('AppBundle\Entity\Movie')
            ->find($id);

        if (!$person) {
            throw $this->createNotFoundException(
                'No person found for id '.$id
            );
        }
        return $this->render('cms/person/edit.html.twig');
    }
}