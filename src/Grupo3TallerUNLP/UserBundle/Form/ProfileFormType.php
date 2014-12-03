<?php

namespace Grupo3TallerUNLP\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
use Grupo3TallerUNLP\UsuarioRedBundle\Form\ProfileType;

class ProfileFormType extends BaseType
{

    public function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildUserForm($builder, $options);
        $builder->remove('username');

        $builder->get('email')->setData(array('label'=>'pedo'));

        $builder->add('usuarioRed', new ProfileType());
    }

    public function getName()
    {
        return 'grupo3tallerunlp_profile';
    }

}