<?php

namespace Grupo3TallerUNLP\ConfiguracionBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ConfiguracionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ConfiguracionRepository extends EntityRepository
{
	public function getArchivoLog(){
		return $this->find(1)->getArchivoDeLog();
	}
}
