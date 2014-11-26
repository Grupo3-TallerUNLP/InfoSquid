<?php

namespace Grupo3TallerUNLP\InformePredefinidoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InformePredefinidoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('frecuenciaTiempo')
			->add('plantilla')
			->add('proximoEnvio' ,null, array( 'widget' => 'single_text', 'format' => 'dd-MM-yyyy'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Grupo3TallerUNLP\InformePredefinidoBundle\Entity\InformePredefinido'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'grupo3tallerunlp_informepredefinidobundle_informepredefinido';
    }
}
