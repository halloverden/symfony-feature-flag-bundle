<?php

namespace HalloVerden\FeatureFlagBundle\Factory;

use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;
use HalloVerden\FeatureFlagBundle\Exception\UnableToBuildFeatureFlagException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface FeatureFlagBuilderInterface
 *
 * @package HalloVerden\FeatureFlagBundle\Factory
 */
interface FeatureFlagBuilderInterface {

  /**
   * @param FeatureFlag     $featureFlag
   * @param InputInterface  $input
   * @param OutputInterface $output
   *
   * @return FeatureFlag
   * @throws UnableToBuildFeatureFlagException
   */
  public function buildFromConsole(FeatureFlag $featureFlag, InputInterface $input, OutputInterface $output): FeatureFlag;

}
