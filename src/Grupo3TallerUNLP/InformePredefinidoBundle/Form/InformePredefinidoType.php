<?php

namespace Grupo3TallerUNLP\InformePredefinidoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Grupo3TallerUNLP\PlantillaBundle\Entity\PlantillaRepository;


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
			->add('proximoEnvio' , 'date' , array( 'widget'=>'single_text', 'format' => 'yyyy-MM-dd'))
        ;
		if ($options['required']) {
				$builder->add('plantilla', 'entity', array(
					'class' => 'Grupo3TallerUNLPPlantillaBundle:Plantilla',
					'query_builder' => function(PlantillaRepository $er){
						return $er->createQueryBuilder('ur')
								  ->leftJoin('ur.informepredefinido', 'us')
								  ->where('us.id IS NULL')
								  ->addOrderBy('ur.nombre', 'ASC')
								  ->addOrderBy('ur.descripcion', 'ASC');
					},
					'label' => 'Plantilla',
				));
			}	
			
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
