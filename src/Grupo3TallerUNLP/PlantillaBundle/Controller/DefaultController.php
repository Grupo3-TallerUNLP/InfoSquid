<?php

namespace Grupo3TallerUNLP\PlantillaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Grupo3TallerUNLPPlantillaBundle:Default:index.html.twig', array('name' => $name));
    }
}
