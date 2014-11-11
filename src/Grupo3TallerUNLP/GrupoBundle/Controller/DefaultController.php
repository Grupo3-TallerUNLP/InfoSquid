<?php

namespace Grupo3TallerUNLP\GrupoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Grupo3TallerUNLPGrupoBundle:Default:index.html.twig', array('name' => $name));
    }
}
