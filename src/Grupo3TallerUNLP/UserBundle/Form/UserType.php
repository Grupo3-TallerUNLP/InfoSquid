<?php

namespace Grupo3TallerUNLP\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['required']) {
            $builder
                ->add('username', null, array('label'=>'Nombre de usuario'))
                ->add('plainPassword', null, array('label'=>'Contraseña'))
                ;
        }

        $builder->add('email');

        if ($options['required']) {
            $builder->add('usuariored', 'entity', array(
                'class' => 'Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed',
                'label' => 'Usuario de Red',
            ));
        }

        $builder
            ->add('administrador', 'checkbox', array(
                'label'    => 'Administrador',
                'required' => false,
            ))
            ->add('enabled', null, array('label'=>'Activado'))
            ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Grupo3TallerUNLP\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'grupo3tallerunlp_userbundle_user';
    }
}
