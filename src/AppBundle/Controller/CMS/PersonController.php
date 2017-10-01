<?php
/**
 * Created by PhpStorm.
 * User: Macola
 * Date: 30.9.2017.
 * Time: 15:05
 */

namespace AppBundle\Controller\CMS;

use AppBundle\Entity\Person;
use AppBundle\Form\PersonFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PersonController extends Controller
{
    /**
     * @Route("/cms/person/list", name="cms_list_person")
     */
    public function listPersonAction()
    {
        $persons = $this->getDoctrine()
            ->getRepository('AppBundle:Person')
            ->findAll();
        return $this->render('cms/person/list.html.twig', array(
            'persons' => $persons
        ));
    }
    /**
     * @Route("/cms/person/create", name="cms_create_person")
     */
    public function addPersonAction(Request $request)
    {
        $form = $this->createForm(PersonFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $person = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            $this->addFlash('success', 'Person created');

            return $this->redirectToRoute('cms_list_person');
        }

        return $this->render('cms/person/create.html.twig',[
            'personForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/cms/person/edit/{id}", name="cms_edit_person")
     */
    public function editPersonAction(Request $request, Person $person)
    {
        $form = $this->createForm(PersonFormType::class, $person);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $person = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            $this->addFlash('success', 'Person updated');

            return $this->redirectToRoute('cms_list_person');
        }

        return $this->render('cms/person/edit.html.twig',[
            'personForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/cms/person/delete/{id}", name="cms_delete_person")
     */
    public function deleteMovieAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')->find($id);

        if (!$person) {
            throw $this->createNotFoundException(
                'No movie found for id '.$id
            );
        }

        $this->addFlash('success', 'Person deleted');

        $em->remove($person);
        $em->flush();

        return $this->redirectToRoute('cms_list_person');
    }
    /**
     * @Route("/cms/person/{id}", name="cms_show_person")
     */
    public function showMovieAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')->find($id);

        if (!$person) {
            throw $this->createNotFoundException(
                'No person found for id '.$id
            );
        }

        return $this->render('cms/person/show.html.twig', [
            'person' => $person
        ]);
    }
}