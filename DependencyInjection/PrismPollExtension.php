<?php

namespace Prism\PollBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PrismPollExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('prism_poll.poll_entity', $config['entity']['poll']);
        $container->setParameter('prism_poll.opinion_entity', $config['entity']['opinion']);

        $container->setParameter('prism_poll.poll_form', $config['form']['poll']);
        $container->setParameter('prism_poll.opinion_form', $config['form']['opinion']);
        $container->setParameter('prism_poll.vote_form', $config['form']['vote']);

        $associations = array(
            'Prism\PollBundle\Entity\BaseOpinion' => array(
                'manyToOne' => array(
                    'fieldName' => 'poll',
                    'targetEntity' => $config['entity']['poll'],
                    "inversedBy" => "opinions",
                    'joinColumns' => array(
                        array(
                            'name' => 'pollId',
                            'referencedColumnName' => 'id',
                            'onDelete' => 'cascade'
                        )
                    )
                )
            ),

            $config['entity']['poll'] => array( // OneToMany association cannot be set on a mapped superclass
                'oneToMany' => array(
                    'fieldName' => 'opinions',
                    'targetEntity' => $config['entity']['opinion'],
                    'mappedBy' => 'poll',
                    'cascade' => array(
                        1 => 'persist'
                    ),
                    'orphanRemoval' => true,
                    'orderBy' => array(
                        'ordering' => 'ASC'
                    )
                )
            )
        );

        $container->setParameter('prism_poll.association_mapping', $associations);
    }
}