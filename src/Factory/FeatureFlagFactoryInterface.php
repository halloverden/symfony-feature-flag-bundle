<?php

namespace HalloVerden\FeatureFlagBundle\Factory;

use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;
use HalloVerden\FeatureFlagBundle\Exception\FeatureFlagAlreadyExistException;
use HalloVerden\FeatureFlagBundle\Exception\UnableToBuildFeatureFlagException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface FeatureFlagFactoryInterface {

  /**
   * @param InputInterface  $input
   * @param OutputInterface $output
   *
   * @return FeatureFlag
   * @throws UnableToBuildFeatureFlagException
   * @throws FeatureFlagAlreadyExistException
   */
  public function createFromConsole(InputInterface $input, OutputInterface $output): FeatureFlag;

  /**
   * @param InputInterface  $input
   * @param OutputInterface $output
   *
   * @return FeatureFlag
   * @throws UnableToBuildFeatureFlagException
   */
  public function updateFromConsole(InputInterface $input, OutputInterface $output): FeatureFlag;

}
