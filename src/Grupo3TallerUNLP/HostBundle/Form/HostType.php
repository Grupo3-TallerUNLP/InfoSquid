<?php

namespace Grupo3TallerUNLP\HostBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class HostType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraint = array(new Assert\Range(array(
            'min'        => 0,
            'max'        => 255,
            'minMessage' => 'El valor debe ser mayor ó igual a cero',
            'maxMessage' => 'El valor debe ser menor ó igual a 255',
        )));

        $builder
            ->add('ipAddress_field1', 'integer', array('constraints' => $constraint))
            ->add('ipAddress_field2', 'integer', array('constraints' => $constraint))
            ->add('ipAddress_field3', 'integer', array('constraints' => $constraint))
            ->add('ipAddress_field4', 'integer', array('constraints' => $constraint))
            ->add('device', null, array('label' => 'Dispositivo', 'required' => True))
            ->add('networkUsers', null,  array('required' => false, 'label'=> 'Usuario'))
            ->add('office', null, array('label' => 'Oficina'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Grupo3TallerUNLP\HostBundle\Entity\Host'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'grupo3tallerunlp_hostbundle_host';
    }
}
