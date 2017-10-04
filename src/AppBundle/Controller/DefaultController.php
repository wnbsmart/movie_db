<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Pagerfanta\Pagerfanta,
    Pagerfanta\Adapter\DoctrineORMAdapter,
    Pagerfanta\Exception\NotValidCurrentPageException;

class DefaultController extends Controller
{
    /*/**
     * @Route("/example/{page}",
     *         name="example",
     *         requirements={"page" = "\d+"},
     *         defaults={"page" = "1"}
     * )
     *
     */
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
        /*
        $entityManager = $this->getDoctrine()->getManager();
        $repository  = $entityManager->getRepository('AppBundle:Person');

        $query = $repository->createQueryBuilder('p')
            ->getQuery();

        $queryBuilder = $entityManager->createQueryBuilder()
            ->select('name')
            ->from('AppBundle:Person', 'u');
        $adapter = new DoctrineORMAdapter($queryBuilder);

        $repo = $this->getDoctrine()->getRepository('AppBundle:Person');

        // returns \Doctrine\ORM\Query object
        $query = $this->getDoctrine()
            ->getRepository('AppBundle:Person');

        $pagerfanta = new Pagerfanta(new DoctrineORMAdapter($query));
        $pagerfanta->setMaxPerPage(5);

        try {
            $pagerfanta->setCurrentPage($page);
        } catch(NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }

        return $this->render('default/index.html.twig', [
            'examples' => $pagerfanta,
        ]);
        */
    }
}
