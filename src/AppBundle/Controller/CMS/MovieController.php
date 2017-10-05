<?php
/**
 * Created by PhpStorm.
 * User: Macola
 * Date: 30.9.2017.
 * Time: 15:20
 */

namespace AppBundle\Controller\CMS;

use AppBundle\Entity\Movie;
use AppBundle\Entity\Role;
use AppBundle\Form\MovieFormType;
use AppBundle\Form\MoviePersonForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MovieController extends Controller
{
    /**
     * @Route("/cms/movie/{id}/addcrew", name="cms_add_crew_movie")
     */
    public function addCrewAction(Request $request)
    {
        $form = $this->createForm(MoviePersonForm::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

        }

        return $this->render('cms/movie/crew.html.twig',[
            'MoviePersonForm' => $form->createView()
        ]);
    }



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
        $form = $this->createForm(MoviePersonForm::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //grab data for checking existing movies
            $movie_name = $form->get('movie_name')->getData();
            $movie_year = $form->get('movie_year')->getData();
            $result = $this->getDoctrine()
                ->getRepository('AppBundle:Movie')
                ->findOneBy(array('name' => $movie_name, 'year' => $movie_year));

            if($result == null) //if there is no movie with same name and year in DB
            {
                //create new Movie obj., set its value & insert into DB
                $movie = new Movie();
                $movie->setName($form->get('movie_name')->getData());
                $movie->setYear($form->get('movie_year')->getData());
                $movie->setDescription($form->get('movie_description')->getData());

                //grab last inserted movie (for movie_id in Role table)
                $em = $this->getDoctrine()->getManager();
                $em->persist($movie);
                $em->flush();

                //create new Role obj., set its value & insert into DB
                $role = new Role();
                $role->setName($form->get('role_name')->getData());
                $role->setPerson($form->get('person_name')->getData());
                $role->setMovie($movie); //inserting primary key (Movie) into foreign key (Role)

                $em->persist($role);
                $em->flush();

                $this->addFlash('success', 'Movie created');

                return $this->redirectToRoute('cms_list_movie');
            }
            else
            {
                $this->addFlash('warning', 'Movie with the same name and year already exists');
            }

        }

        return $this->render('cms/movie/create.html.twig',[
            'MoviePersonForm' => $form->createView()
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
    /**
     * @Route("/cms/movie/{id}", name="cms_show_movie")
     */
    public function showMovieAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository('AppBundle:Movie')->find($id);

        if (!$movie) {
            throw $this->createNotFoundException(
                'No movie found for id '.$id
            );
        }

        $repository = $this->getDoctrine()
            ->getRepository(Role::class);

        $query = $repository->createQueryBuilder('r')
            ->andWhere('r.movie = :id AND r.name = :actor')
            ->setParameters(['id' => $id, 'actor' => 'actor'])
            ->getQuery();

        if(!empty($query->getResult()))
            $actors = $query->getResult();
        else $actors = null;

        $query = $repository->createQueryBuilder('r')
            ->andWhere('r.movie = :id AND r.name = :writer')
            ->setParameters(['id' => $id, 'writer' => 'writer'])
            ->getQuery();

        if(!empty($query->getResult()))
            $writers = $query->getResult();
        else $writers = null;

        $query = $repository->createQueryBuilder('r')
            ->andWhere('r.movie = :id AND r.name = :director')
            ->setParameters(['id' => $id, 'director' => 'director'])
            ->getQuery();

        if(!empty($query->getResult()))
            $directors = $query->getResult();
        else $directors = null;

        return $this->render('cms/movie/show.html.twig', [
            'movie' => $movie,
            'actors' => $actors,
            'directors' => $directors,
            'writers' => $writers
        ]);
    }




}