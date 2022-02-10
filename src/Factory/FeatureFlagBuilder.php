<?php

namespace HalloVerden\FeatureFlagBundle\Factory;

use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class FeatureFlagBuilder
 *
 * @package HalloVerden\FeatureFlagBundle\Factory
 */
class FeatureFlagBuilder implements FeatureFlagBuilderInterface {

  /**
   * @inheritDoc
   */
  public function buildFromConsole(FeatureFlag $featureFlag, InputInterface $input, OutputInterface $output): FeatureFlag {
    $io = new SymfonyStyle($input, $output);
    return $featureFlag->setNameFromConsole($input, $io)->setDescriptionFromConsole($input, $io);
  }

}
