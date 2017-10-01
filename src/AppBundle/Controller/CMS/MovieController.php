<?php
/**
 * Created by PhpStorm.
 * User: Macola
 * Date: 30.9.2017.
 * Time: 15:20
 */

namespace AppBundle\Controller\CMS;

use AppBundle\Entity\Movie;
use AppBundle\Form\MovieFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MovieController extends Controller
{
    /**
     * @Route("/cms/movie/list", name="cms_list_movie")
     */
    public function listMovieAction()
    {
        $movies = $this->getDoctrine()
            ->getRepository('AppBundle\Entity\Movie')
            ->findAll();
        return $this->render('cms/movie/list.html.twig', array(
            'movies' => $movies
        ));
    }
    /**
     * @Route("/cms/movie/create", name="cms_create_movie")
     */
    public function addMovieAction(Request $request)
    {
        $form = $this->createForm(MovieFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $movie = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            $this->addFlash('success', 'Movie created');

            return $this->redirectToRoute('cms_list_movie');
        }

        return $this->render('cms/movie/create.html.twig',[
            'movieForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/cms/movie/edit/{id}", name="cms_edit_movie")
     */
    public function editMovieAction(Request $request, Movie $movie)
    {
        $form = $this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $movie = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            $this->addFlash('success', 'Movie updated');

            return $this->redirectToRoute('cms_list_movie');
        }

        return $this->render('cms/movie/edit.html.twig',[
            'movieForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/cms/movie/delete/{id}", name="cms_delete_movie")
     */
    public function deleteMovieAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository('AppBundle:Movie')->find($id);

        if (!$movie) {
            throw $this->createNotFoundException(
                'No movie found for id '.$id
            );
        }

        $this->addFlash('success', 'Movie deleted');

        $em->remove($movie);
        $em->flush();

        return $this->redirectToRoute('cms_list_movie');
    }
}