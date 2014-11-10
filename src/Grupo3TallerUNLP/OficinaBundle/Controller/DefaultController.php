<?php

namespace Grupo3TallerUNLP\OficinaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Grupo3TallerUNLPOficinaBundle:Default:index.html.twig', array('name' => $name));
    }
}
