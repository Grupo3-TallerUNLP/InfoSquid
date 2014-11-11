<?php

namespace Grupo3TallerUNLP\ConfiguracionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfiguracionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('paginacion')
            ->add('directorioLogsAntiguos')
            ->add('archivoDeLog')
            ->add('tiempoMantenimiento')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Grupo3TallerUNLP\ConfiguracionBundle\Entity\Configuracion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'grupo3tallerunlp_configuracionbundle_configuracion';
    }
}
