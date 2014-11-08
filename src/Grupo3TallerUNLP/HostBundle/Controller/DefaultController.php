<?php

namespace Grupo3TallerUNLP\HostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Grupo3TallerUNLPHostBundle:Default:index.html.twig', array('name' => $name));
    }
}
