<?php

namespace AmazonS3\ViewBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class ViewExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setDefinition(
            's3_view.service',
            new Definition('ZendService\Amazon\S3\S3', array(
                $config['client_data']['key'],
                $config['client_data']['secret'],
                $config['client_data']['region']
            ))
        );

        $container->setParameter('s3_view.bucket_name', $config['client_data']['bucket_name']);
        $container->setParameter('s3_view.cdn_url', rtrim($config['client_data']['cdn_url'], '/'));
    }

    public function getAlias()
    {
        return 'view';
    }
}
