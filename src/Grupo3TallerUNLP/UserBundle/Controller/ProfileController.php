<?php

namespace Grupo3TallerUNLP\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

class ProfileController extends BaseController
{

    public function editAction()
    {
        $currentUser = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($currentUser) || !$currentUser instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $request = $this->container->get('request');
        $data = $request->get('fos_user_profile_form', null);

        $error = false;

        $form = $this->container->get('fos_user.profile.form');
        $formHandler = $this->container->get('fos_user.profile.form.handler');

        if (!empty($data)) {
            if (!empty($data['usuarioRed']['dNI'])) {
                if (!is_numeric($data['usuarioRed']['dNI']) || ($data['usuarioRed']['dNI']<999999)) {
                    $this->container->get('session')->getFlashBag()->add('error', 'El DNI debe ser un valor numerico y mayor a 999999');

                    return new RedirectResponse($this->container->get('router')->generate('fos_user_profile_edit'));
                }
            }
        }

        $process = $formHandler->process($currentUser);
        if ($process) {
            $this->container->get('session')->getFlashBag()->add('success', 'Su perfil ha sido actualizado');

            return new RedirectResponse($this->getRedirectionUrl($currentUser));
        }

        $em = $this->container->get('doctrine')->getManager();
        $user = $em
            ->getRepository('Grupo3TallerUNLPUserBundle:User')
            ->findOneByEmail($request->request->get('fos_user_profile_form[email]', null, true))
            ;

        if ($user && $user->getId() != $currentUser->getId()) {
            $this->container->get('session')->getFlashBag()->add('error', 'Ya existe un usuario con ese email');
        } elseif (!empty($data)) {
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($currentUser);
            $password = $encoder->encodePassword($data['current_password'], $currentUser->getSalt());
            if ($password != $currentUser->getPassword()) {
                $this->container->get('session')->getFlashBag()->add('error', 'La contraseña es incorrecta');
            } else {
                $this->container->get('session')->getFlashBag()->add('error', 'Alguno de los campos no es válido');
            }
        }

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
            array('form' => $form->createView())
        );
    }

}
