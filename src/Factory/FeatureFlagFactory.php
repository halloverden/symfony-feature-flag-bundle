<?php

namespace HalloVerden\FeatureFlagBundle\Factory;

use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;
use HalloVerden\FeatureFlagBundle\Exception\FeatureFlagAlreadyExistException;
use HalloVerden\FeatureFlagBundle\Repository\FeatureFlagRepositoryInterface;
use HalloVerden\HttpExceptions\NotFoundException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FeatureFlagFactoryManager
 *
 * @package HalloVerden\FeatureFlagBundle\Factory
 */
class FeatureFlagFactory implements FeatureFlagFactoryInterface {
  private FeatureFlagRepositoryInterface $featureFlagRepository;

  /**
   * @param FeatureFlagRepositoryInterface $featureFlagRepository
   */
  public function __construct(FeatureFlagRepositoryInterface $featureFlagRepository) {
    $this->featureFlagRepository = $featureFlagRepository;
  }

  /**
   * @inheritDoc
   */
  public function createFromConsole(InputInterface $input, OutputInterface $output): FeatureFlag {
    $typeOrClass = $input->getArgument(FeatureFlag::PROPERTY_TYPE);
    $class = $this->featureFlagRepository->getClass($typeOrClass);

    try {
      $this->featureFlagRepository->getFeatureFlag($class);
      throw new FeatureFlagAlreadyExistException($typeOrClass);
    } catch (NotFoundException $exception) {
      // no-op
    }

    return $this->featureFlagRepository->createFeatureFlag($class::createFromConsole($input, $output));
  }

  /**
   * @inheritDoc
   */
  public function updateFromConsole(InputInterface $input, OutputInterface $output): FeatureFlag {
    return $this->featureFlagRepository->updateFeatureFlag(
      $this->featureFlagRepository->getFeatureFlag($input->getArgument(FeatureFlag::PROPERTY_TYPE))->setFromConsole($input, $output)
    );
  }

}
