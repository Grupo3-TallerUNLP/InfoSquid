<?php

namespace Grupo3TallerUNLP\UsuarioRedBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Grupo3TallerUNLPUsuarioRedBundle:Default:index.html.twig', array('name' => $name));
    }
}
