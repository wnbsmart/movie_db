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
use AppBundle\Form\RoleForm;
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
        $movie = new Movie();
        $form = $this->createForm(MovieFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $movie->setName($form->get('name')->getData());
            $movie->setYear($form->get('year')->getData());
            $movie->setDescription($form->get('description')->getData());

            $file = $form->get('imagePath')->getData();
            $img_name = time() . $file->getClientOriginalName();
            $path_parts = pathinfo($img_name); //variable for checking file's extension (in elseif ->)

            if(!is_object($form->get('imagePath')->getData())) //if file is NOT being uploaded
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($movie);
                $em->flush();

                $this->addFlash('success', 'Movie created');
                return $this->redirectToRoute('cms_list_movie');
            }
            elseif($path_parts['extension'] == "jpg" || $path_parts['extension'] == "jpeg" || $path_parts['extension'] == "png")
            {   //if file has extension .jpg, .jpeg or .png
                $movie->setImagePath($img_name);

                $file->move($this->getParameter('upload_directory'), $img_name);

                $em = $this->getDoctrine()->getManager();
                $em->persist($movie);
                $em->flush();

                $this->addFlash('success', 'Movie created');
                return $this->redirectToRoute('cms_list_movie');
            }
            else{
                $this->addFlash('warning', 'Chosen file must be an image (.jpg, .jpeg, .png)');
            }
        }
        return $this->render('cms/movie/create.html.twig',[
            'MovieFormType' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cms/movie/{id}/addcrew", name="cms_add_crew_movie")
     */
    public function addCrewAction(Request $request, Movie $movie)
    {
        $form = $this->createForm(RoleForm::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //create new Role obj., set its value & insert into DB
            $role = new Role();
            $role->setName($form->get('name')->getData());
            $role->setPerson($form->get('person')->getData());
            $role->setMovie($movie); //inserting primary key (Movie) into foreign key (Role)

            $em = $this->getDoctrine()->getManager();
            $em->persist($role);
            $em->flush();

            $this->addFlash('success', 'Person added');
        }

        $roles = $this->getDoctrine()
            ->getRepository('AppBundle:Role')
            ->findByMovie($movie);

        return $this->render('cms/movie/crew.html.twig',[
            'RoleForm' => $form->createView(),
            'movie' => $movie,
            'roles' => $roles,
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
     * @Route("/cms/movie/edit/{id}", name="cms_edit_movie")
     */
    public function editMovieAction(Request $request, Movie $movie)
    {
        $form = $this->createForm(MovieFormType::class, $movie);

        //it's for showing existing image while editing,
        //if person chooses wrong format of file,
        //"Existing photo: " text would show up
        $if_image_exists = $movie->getImagePath();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $m_id = $movie->getId(); //for redirecting...

            if(!is_object($form->get('imagePath')->getData()) && $movie->getImagePath() == null) //file isn't set & movie HASN'T image already
            {
                $em->persist($movie);
                $em->flush();
                $this->addFlash('success', 'Movie updated');
                return $this->redirectToRoute('cms_show_movie', ['id'=>$m_id]);
            }
            elseif(!is_object($form->get('imagePath')->getData()) && $movie->getImagePath() != null) //file isn't set & movie HAS image already
            {
                $movie->setName($form->get('name')->getData());
                $movie->setYear($form->get('year')->getData());
                $movie->setDescription($form->get('description')->getData());
                $em->persist($movie);
                $em->flush();
                $this->addFlash('success', 'Movie updated');
                return $this->redirectToRoute('cms_show_movie', ['id'=>$m_id]);
            }
            elseif(is_object($form->get('imagePath'))) //file IS set
            {
                $file = $form->get('imagePath')->getData();
                $img_name = time() . $file->getClientOriginalName();

                $path_parts = pathinfo($img_name);
                if($path_parts['extension'] == "jpg" || $path_parts['extension'] == "jpeg" || $path_parts['extension'] == "png")
                {
                    $movie->setImagePath($img_name);

                    $file->move($this->getParameter('upload_directory'), $img_name);

                    $em->persist($movie);
                    $em->flush();

                    $this->addFlash('success', 'Movie updated');
                    return $this->redirectToRoute('cms_show_movie', ['id'=>$m_id]);
                }
                else
                {
                    $this->addFlash('warning', 'Chosen file must be an image (.jpg, .jpeg, .png)');
                }
            }
        }

        return $this->render('cms/movie/edit.html.twig',[
            'movieForm' => $form->createView(),
            'movie' => $if_image_exists,
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