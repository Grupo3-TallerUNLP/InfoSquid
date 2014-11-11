<?php

namespace Grupo3TallerUNLP\ConfiguracionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Grupo3TallerUNLPConfiguracionBundle:Default:index.html.twig', array('name' => $name));
    }
}
