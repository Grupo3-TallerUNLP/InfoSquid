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
        $builder
            ->add('username')
            ->add('password', 'password', array('required' => $options['required']))
            ->add('email')
            ->add('usuariored', 'entity', array('class'=>'Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed'))
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
