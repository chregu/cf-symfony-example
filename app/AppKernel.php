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
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Acme\DemoBundle\AcmeDemoBundle(),
            new Ekino\Bundle\NewRelicBundle\EkinoNewRelicBundle(),
            new AppBundle\AppBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
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

   /**
     * {@inheritdoc}
     *
     * @api
     */
    public function getCacheDir()
    {
        if (isset($_ENV['VCAP_APPLICATION'])) {
            return sys_get_temp_dir() . '/symfony2/cache';
        }

        return parent::getCacheDir();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function getLogDir()
    {
        if (isset($_ENV['VCAP_APPLICATION'])) {
            return sys_get_temp_dir() . '/symfony2/logs';
        }


        return parent::getLogDir();
    }

    /**
     * Gets the environment parameters from VCAP_SERVICES
     *
     * @return array An array of parameters
     */
    protected function getEnvParameters()
    {
        $parameters = array();
        $vcap_services = getenv('VCAP_SERVICES');
        if ($vcap_services) {
            $vcap_services = json_decode(getenv('VCAP_SERVICES'), true);
            foreach ($vcap_services as $name => $service) {
                foreach ($service as $s) {
                    $parameters = array_merge($parameters, $this->getVcapParameter($s));
                }
            }
        }

        return array_merge(parent::getEnvParameters(), $parameters);
    }

    /**
     * Gets the environment parameters from a service in VCAP_SERVICES
     *
     * @param array $service An array with the service definition
     *
     * @return array An array of parameters
     */
    protected function getVcapParameter(array $service)
    {
        $params = array();
        foreach ($service['credentials'] as $key => $value) {
            $params["vcap." . $service['name'] . "." . $key] = $value;
        }
        return $params;
    }

}
