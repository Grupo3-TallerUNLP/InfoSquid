<?php

namespace Grupo3TallerUNLP\InformePredefinidoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Grupo3TallerUNLPInformePredefinidoBundle:Default:index.html.twig', array('name' => $name));
    }
}
