<?php

namespace Prism\PollBundle\Listener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

/**
 * LoadClassMetadata Listener
 */
class LoadClassMetadataListener
{
    private $container;

    /**
     * LoadClassMetadata
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        $association_mapping = $this->container->getParameter('prism_poll.association_mapping');

        if (array_key_exists($classMetadata->name, $association_mapping)) {
            foreach ($association_mapping[$classMetadata->name] as $type => $parameters) {
                call_user_func(array($classMetadata, 'map' . ucfirst($type)), $parameters);
            }
        }
    }

    /**
     * Set Container
     *
     * @param \Symfony\Component\DependencyInjection\Container $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }
}