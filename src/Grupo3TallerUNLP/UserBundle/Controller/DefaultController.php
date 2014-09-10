<?php

namespace Grupo3TallerUNLP\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Grupo3TallerUNLPUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
