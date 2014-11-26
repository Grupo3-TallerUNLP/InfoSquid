<?php

namespace Grupo3TallerUNLP\InformeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Grupo3TallerUNLPInformeBundle:Default:index.html.twig', array('name' => $name));
    }
}
