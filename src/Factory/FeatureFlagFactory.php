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
  private FeatureFlagBuilderInterface $featureFlagBuilder;

  /**
   * @param FeatureFlagRepositoryInterface $featureFlagRepository
   * @param FeatureFlagBuilderInterface    $featureFlagBuilder
   */
  public function __construct(FeatureFlagRepositoryInterface $featureFlagRepository, FeatureFlagBuilderInterface $featureFlagBuilder) {
    $this->featureFlagRepository = $featureFlagRepository;
    $this->featureFlagBuilder = $featureFlagBuilder;
  }

  /**
   * @inheritDoc
   */
  public function createFromConsole(InputInterface $input, OutputInterface $output): FeatureFlag {
    $typeOrClass = $input->getArgument(self::INPUT_ARGUMENT_TYPE);
    $class = $this->featureFlagRepository->getClass($typeOrClass);

    try {
      $this->featureFlagRepository->getFeatureFlag($class);
      throw new FeatureFlagAlreadyExistException($typeOrClass);
    } catch (NotFoundException $exception) {
      // no-op
    }

    return $this->featureFlagRepository->createFeatureFlag($this->featureFlagBuilder->buildFromConsole(new $class(), $input, $output));
  }

  /**
   * @inheritDoc
   */
  public function updateFromConsole(InputInterface $input, OutputInterface $output): FeatureFlag {
    return $this->featureFlagRepository->updateFeatureFlag(
      $this->featureFlagBuilder->buildFromConsole($this->featureFlagRepository->getFeatureFlag($input->getArgument(self::INPUT_ARGUMENT_TYPE)), $input, $output)
    );
  }

}
