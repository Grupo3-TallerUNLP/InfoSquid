<?php

namespace Grupo3TallerUNLP\PlantillaBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Grupo3TallerUNLP\PlantillaBundle\Entity\Filtro;
use Grupo3TallerUNLP\PlantillaBundle\Entity\FiltroRepository;
use Grupo3TallerUNLP\GrupoBundle\Entity\Grupo;
use Grupo3TallerUNLP\SitioBundle\Entity\Sitio;
use Grupo3TallerUNLP\OficinaBundle\Entity\Oficina;
use Grupo3TallerUNLP\UsuarioBundle\Entity\Usuario;

class PlantillaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
	 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion', 'text', array('required'=>false))
			->add('usuariosistema', null, array('required' => true))
		
        ;
    }
    	
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Grupo3TallerUNLP\PlantillaBundle\Entity\Plantilla'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'grupo3tallerunlp_plantillabundle_plantilla';
    }
}
