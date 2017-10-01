<?php
/**
 * Created by PhpStorm.
 * User: Macola
 * Date: 30.9.2017.
 * Time: 15:20
 */

namespace AppBundle\Controller\CMS;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MovieController extends Controller
{
    /**
     * @Route("/cms/movie/create", name="cms_create_movie")
     */
    public function addMovieAction(Request $request)
    {
        return $this->render('cms/movie/create.html.twig');
    }
    /**
     * @Route("/cms/movie/edit/{id}", name="cms_edit_movie")
     * @Method("GET")
     */
    public function editMovieAction($id, Request $request)
    {
        $movie = $this->getDoctrine()
            ->getRepository('AppBundle\Entity\Movie')
            ->find($id);

        if (!$movie) {
            throw $this->createNotFoundException(
                'No movie found for id '.$id
            );
        }

        return $this->render('cms/movie/edit.html.twig', array(
            'movie' => $movie
        ));
    }

    /**
     * @Route("/movie/delete/{id}", name="cms_delete_movie")
     * @Method("GET")
     */
    public function deleteMovieAction($id, Request $request)
    {
        $movie = $this->getDoctrine()
            ->getRepository('AppBundle\Entity\Movie')
            ->find($id);

        if (!$movie) {
            throw $this->createNotFoundException(
                'No movie found for id '.$id
            );
        }
        return $this->render('cms/movie/delete.html.twig', array(
            'movie' => $movie
        ));
    }
}