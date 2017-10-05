<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Pagerfanta\Pagerfanta,
    Pagerfanta\Adapter\DoctrineORMAdapter,
    Pagerfanta\Exception\NotValidCurrentPageException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/cms", name="cms")
     */
    public function cmsAction()
    {
        $movies = $this->getDoctrine()
            ->getRepository('AppBundle:Movie')
            ->findAll();

        return $this->render('cms/index.html.twig',['movies' => $movies]);
    }

    /**
     * @Route("/{page}",
     *         name="home",
     *         requirements={"page" = "\d+"},
     *         defaults={"page" = "1"})
     */

    public function pagerAction($page)
    {
        //grab all movies
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em
            ->getRepository('AppBundle:Movie')->createQueryBuilder('m')
            ->getQuery();

        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(4); //amount of movies per page

        try {
            $pager->setCurrentPage($page);
        } catch(NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }

        //grab all roles
        $roles = $this->getDoctrine()
            ->getRepository('AppBundle:Role')
            ->findAll();

        return $this->render('/default/index.html.twig',[
            'my_pager' => $pager,
            'roles' => $roles,
        ]);
    }
}
