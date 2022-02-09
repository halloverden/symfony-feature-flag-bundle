<?php

namespace HalloVerden\FeatureFlagBundle\Factory;

use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;
use HalloVerden\FeatureFlagBundle\Exception\FeatureFlagAlreadyExistException;
use HalloVerden\FeatureFlagBundle\Exception\UnableToBuildFeatureFlagException;
use HalloVerden\FeatureFlagBundle\Repository\FeatureFlagRepositoryInterface;
use HalloVerden\HttpExceptions\NotFoundException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class FeatureFlagFactory
 *
 * @package HalloVerden\FeatureFlagBundle\Factory
 */
class FeatureFlagFactory implements FeatureFlagFactoryInterface {
  const INPUT_ARGUMENT_TYPE = 'type';
  const INPUT_OPTION_NAME = 'name';
  const INPUT_OPTION_DESCRIPTION = 'description';

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
  public function getDefinition() {
    return [
      new InputArgument(self::INPUT_ARGUMENT_TYPE, InputArgument::REQUIRED, 'Type of feature flag'),
      new InputOption(self::INPUT_OPTION_NAME, null, InputOption::VALUE_REQUIRED, 'Name of the feature flag'),
      new InputOption(self::INPUT_OPTION_DESCRIPTION, null, InputOption::VALUE_REQUIRED, 'Description of the feature flag'),
    ];
  }

  /**
   * @inheritDoc
   */
  public function createFromConsole(InputInterface $input, OutputInterface $output): FeatureFlag {
    $type = $input->getArgument(self::INPUT_ARGUMENT_TYPE);
    $class = $this->getClass($type);

    try {
      $this->featureFlagRepository->getFeatureFlag($class);
      throw new FeatureFlagAlreadyExistException($type);
    } catch (NotFoundException $exception) {
      // no-op
    }

    return $this->featureFlagRepository->createFeatureFlag($this->buildFeatureFlag(new $class(), $input, $output));
  }

  /**
   * @inheritDoc
   */
  public function updateFromConsole(InputInterface $input, OutputInterface $output): FeatureFlag {
    return $this->featureFlagRepository->updateFeatureFlag(
      $this->buildFeatureFlag(
        $this->featureFlagRepository->getFeatureFlag($input->getArgument(self::INPUT_ARGUMENT_TYPE)),
        $input,
        $output,
        false
      )
    );
  }

  /**
   * @param string $type
   *
   * @return string
   */
  protected function getClass(string $type): string {
    return $this->featureFlagRepository->getClass($type);
  }


  /**
   * @param FeatureFlag     $featureFlag
   * @param InputInterface  $input
   * @param OutputInterface $output
   * @param bool            $new
   *
   * @return FeatureFlag
   * @throws UnableToBuildFeatureFlagException
   */
  protected function buildFeatureFlag(FeatureFlag $featureFlag, InputInterface $input, OutputInterface $output, bool $new = true): FeatureFlag {
    $io = new SymfonyStyle($input, $output);

    $notNull = function ($value) {
      if (null === $value) {
        throw new \Exception("Value can't be null");
      }

      return $value;
    };

    $name = $input->getOption(self::INPUT_OPTION_NAME) ?: $io->ask('Name', $new ? null : $featureFlag->getName(), $notNull);
    if (null === $name) {
      throw new UnableToBuildFeatureFlagException('Missing name');
    }

    $description = $input->getOption(self::INPUT_OPTION_DESCRIPTION) ?: $io->ask('Description', $new ? null : $featureFlag->getDescription(), $notNull);
    if (null === $description) {
      throw new UnableToBuildFeatureFlagException('Missing description');
    }

    return $featureFlag->setName($name)->setDescription($description);
  }

}
