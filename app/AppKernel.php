<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Grupo3TallerUNLP\UserBundle\Grupo3TallerUNLPUserBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
            new Grupo3TallerUNLP\MenuBundle\Grupo3TallerUNLPMenuBundle(),
            new Grupo3TallerUNLP\UsuarioRedBundle\Grupo3TallerUNLPUsuarioRedBundle(),
            new Grupo3TallerUNLP\HostBundle\Grupo3TallerUNLPHostBundle(),
            new Grupo3TallerUNLP\OficinaBundle\Grupo3TallerUNLPOficinaBundle(),
            new Grupo3TallerUNLP\GrupoBundle\Grupo3TallerUNLPGrupoBundle(),
            new Grupo3TallerUNLP\SitioBundle\Grupo3TallerUNLPSitioBundle(),
			new Grupo3TallerUNLP\PlantillaBundle\Grupo3TallerUNLPPlantillaBundle(),
            new Grupo3TallerUNLP\InformePredefinidoBundle\Grupo3TallerUNLPInformePredefinidoBundle(),
            new Grupo3TallerUNLP\ConfiguracionBundle\Grupo3TallerUNLPConfiguracionBundle(),
            new Grupo3TallerUNLP\InformeBundle\Grupo3TallerUNLPInformeBundle(),
			new Ob\HighchartsBundle\ObHighchartsBundle(),
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
			
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Acme\DemoBundle\AcmeDemoBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
