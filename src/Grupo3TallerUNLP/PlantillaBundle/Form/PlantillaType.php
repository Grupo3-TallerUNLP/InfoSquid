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
	private function listFiltros()
	{
		#$filtro= $this->getDoctrine()->getRepository('Grupo3TallerUNLPPlantillaBundle:Filtro')->findAll();
        //$entities = $filtro->findAll();

		#return $entities;
	}
	 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion', 'text', array('required'=>false))
			//add('filtro', 'choice', array('mapped'=>false, 'choices' =>$this-> buildChoicesFiltro()))
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
	 
	/**protected function buildChoicesFiltro(){
		$choices = [];
		$table = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPPlantillaBundle:FiltroRed')->findAll();
		
		foreach($table as $t) {
			$choices[$t->getId()] = $t->__toString();
		}
		return $choices;
	}
	**/
	
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
