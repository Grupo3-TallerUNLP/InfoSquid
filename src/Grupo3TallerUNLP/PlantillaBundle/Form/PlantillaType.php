<?php

namespace Grupo3TallerUNLP\PlantillaBundle\Form;

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
	private function listFiltros()
	{
		// $em = $this->getDoctrine()->getManager();
		$filtro = new FiltroRepository;
        $entities = $filtro->findAll();
		var_dump($entities);
		die();
		return $entities;
	}
	 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$filtros = $this->listFiltros();
        $builder
            ->add('nombre')
            ->add('descripcion', 'text', array('required'=>false))
			->add($filtros[0].id, 'text', array('name'=>$filtros[0]))
			// ->add('filtros', $options['datos']['filtros'])
			// ->add('grupos', $options['datos']['grupos'])
			// ->add('sitios', $options['datos']['sitios'])
			// ->add('oficinas', $options['datos']['oficinas'])
			// ->add('usuarios', $options['datos']['usuarios'])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
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
