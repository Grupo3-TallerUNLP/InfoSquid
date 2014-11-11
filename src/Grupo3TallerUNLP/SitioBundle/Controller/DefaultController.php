<?php

namespace Grupo3TallerUNLP\SitioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Grupo3TallerUNLPSitioBundle:Default:index.html.twig', array('name' => $name));
    }
}
