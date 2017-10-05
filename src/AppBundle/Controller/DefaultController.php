<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Pagerfanta\Pagerfanta,
    Pagerfanta\Adapter\DoctrineORMAdapter,
    Pagerfanta\Exception\NotValidCurrentPageException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/{page}",
     *         name="home",
     *         requirements={"page" = "\d+"},
     *         defaults={"page" = "1"})
     */

    public function pagerAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Movie');
        $queryBuilder = $repository->createQueryBuilder('m')
            ->getQuery();

        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(2);

        try {
            $pager->setCurrentPage($page);
        } catch(NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }

        return $this->render('/default/index.html.twig',
            ['my_pager' => $pager]);
    }
}
