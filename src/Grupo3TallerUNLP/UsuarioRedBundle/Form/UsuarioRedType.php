<?php

namespace Grupo3TallerUNLP\UsuarioRedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioRedType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', null, array('attr'=> array( 'maxlength'=> '50' )))
            ->add('apellido', null, array('attr'=> array( 'maxlength'=> '50' )))
            ->add('cargo', 'text',  array('required' => false, 'attr'=> array( 'maxlength'=> '50' )))
            ->add('dNI','text',  array('required' => false, 'attr'=> array( 'maxlength'=> '8' )))
			->add('oficina', null, array('required'=>true))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'grupo3tallerunlp_usuarioredbundle_usuariored';
    }
}
