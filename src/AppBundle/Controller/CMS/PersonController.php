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

        $if_image_exists = $person->getImagePath(); //grabs image, but it's also information does movie even contain an image

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $p_id = $person->getId(); //for redirecting...

            if(!is_object($form->get('imagePath')->getData()) && $if_image_exists == false) //file isn't set & person HASN'T image already
            {
                $em->persist($person);
                $em->flush();
                $this->addFlash('success', 'Person updated');
                return $this->redirectToRoute('cms_show_person', ['id'=>$p_id]);
            }
            elseif(!is_object($form->get('imagePath')->getData()) && $if_image_exists == true) //file isn't set & person HAS image already
            {
                $person->setImagePath($if_image_exists);
                $em->persist($person);
                $em->flush();
                $this->addFlash('success', 'Person updated');
                return $this->redirectToRoute('cms_show_person', ['id'=>$p_id]);
            }
            elseif(is_object($form->get('imagePath'))) //file IS set
            {
                $file = $form->get('imagePath')->getData();
                $img_name = time() . $file->getClientOriginalName();

                $path_parts = pathinfo($img_name);
                if($path_parts['extension'] == "jpg" || $path_parts['extension'] == "jpeg" || $path_parts['extension'] == "png")
                {
                    $person->setImagePath($img_name);

                    $file->move($this->getParameter('upload_directory'), $img_name);

                    $em->persist($person);
                    $em->flush();

                    $this->addFlash('success', 'Person updated');
                    return $this->redirectToRoute('cms_show_person', ['id'=>$p_id]);
                }
                else
                {
                    $this->addFlash('warning', 'Chosen file must be an image (.jpg, .jpeg, .png)');
                }
            }
        }

        return $this->render('cms/person/edit.html.twig',[
            'personForm' => $form->createView(),
            'image' => $if_image_exists,
        ]);
    }
    /**
     * @Route("/cms/person/delete/{id}", name="cms_delete_person")
     */
    public function deletePersonAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')->find($id);

        if (!$person) {
            throw $this->createNotFoundException(
                'No person found for id '.$id
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
    public function showPersonAction($id)
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
            'd_movies' => $d_movies
        ]);
    }
}