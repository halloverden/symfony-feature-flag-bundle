<?php

namespace HalloVerden\FeatureFlagBundle\DependencyInjection;

use HalloVerden\FeatureFlagBundle\Factory\FeatureFlagBuilderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class HalloVerdenFeatureFlagExtension
 *
 * @package HalloVerden\FeatureFlagBundle\DependencyInjection
 */
class HalloVerdenFeatureFlagExtension extends Extension {

  /**
   * @inheritDoc
   * @throws \Exception
   */
  public function load(array $configs, ContainerBuilder $container) {
    $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
    $loader->load('services.yaml');
  }

}
