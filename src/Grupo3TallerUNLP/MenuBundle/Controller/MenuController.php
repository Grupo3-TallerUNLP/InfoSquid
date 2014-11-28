<?php

namespace Grupo3TallerUNLP\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function navbarAction()
    {
		if($this->get('security.context')->isGranted('ROLE_ADMIN')) {
			return $this->render('Grupo3TallerUNLPMenuBundle:Menu:navbar.html.twig');
		}
		else {
			return $this->render('Grupo3TallerUNLPMenuBundle:Menu:navbaruser.html.twig');
		}
        
    }
	
	public function sidebarAction()
    {
		if($this->get('security.context')->isGranted('ROLE_ADMIN')) {
			return $this->render('Grupo3TallerUNLPMenuBundle:Menu:sidebar.html.twig');
		}
		else {
			return $this->render('Grupo3TallerUNLPMenuBundle:Menu:sidebaruser.html.twig');
		}
        
    }
	
}
