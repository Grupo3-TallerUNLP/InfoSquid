<?php

namespace Grupo3TallerUNLP\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\Controller\SecurityController as BaseController;

class DefaultController extends BaseController
{
    public function indexAction($name)
    {
        return $this->render('Grupo3TallerUNLPUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
