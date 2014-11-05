<?php

namespace Grupo3TallerUNLP\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Grupo3TallerUNLPMenuBundle:Default:index.html.twig', array('name' => $name));
    }
}
