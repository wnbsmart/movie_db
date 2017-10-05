<?php
/**
 * Created by PhpStorm.
 * User: Macola
 * Date: 30.9.2017.
 * Time: 15:05
 */

namespace AppBundle\Controller\CMS;

use AppBundle\Entity\Person;
use AppBundle\Entity\Role;
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

        $repository = $this->getDoctrine()
            ->getRepository(Role::class);

        //find all movies where person acted (a_movies)
        $query = $repository->createQueryBuilder('r')
            ->andWhere('r.person = :id AND r.name = :actor')
            ->setParameters(['id' => $id, 'actor' => 'actor'])
            ->getQuery();

        if(!empty($query->getResult()))
            $a_movies = $query->getResult();
        else $a_movies = null;

        //find all movies where person was a writer (w_movies)
        $query = $repository->createQueryBuilder('r')
            ->andWhere('r.person = :id AND r.name = :writer')
            ->setParameters(['id' => $id, 'writer' => 'writer'])
            ->getQuery();

        if(!empty($query->getResult()))
            $w_movies = $query->getResult();
        else $w_movies = null;

        //find all movies where person directed them (d_movies)
        $query = $repository->createQueryBuilder('r')
            ->andWhere('r.person = :id AND r.name = :director')
            ->setParameters(['id' => $id, 'director' => 'director'])
            ->getQuery();

        if(!empty($query->getResult()))
            $d_movies = $query->getResult();
        else $d_movies = null;

        return $this->render('cms/person/show.html.twig', [
            'person' => $person,
            'a_movies' => $a_movies,
            'w_movies' => $w_movies,
            'd_movies' => $d_movies,
        ]);
    }
}