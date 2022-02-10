<?php

namespace HalloVerden\FeatureFlagBundle\Command;

use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;
use HalloVerden\FeatureFlagBundle\Exception\UnableToBuildFeatureFlagException;
use HalloVerden\FeatureFlagBundle\Factory\FeatureFlagFactoryInterface;
use HalloVerden\HttpExceptions\NotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class FeatureFlagUpdateCommand
 *
 * @package HalloVerden\FeatureFlagBundle\Command
 */
class FeatureFlagUpdateCommand extends Command {
  protected static $defaultName = 'feature-flag:update';
  protected static $defaultDescription = 'update feature flag';

  private FeatureFlagFactoryInterface $featureFlagFactory;

  /**
   * @param FeatureFlagFactoryInterface $featureFlagFactory
   */
  public function __construct(FeatureFlagFactoryInterface $featureFlagFactory) {
    $this->featureFlagFactory = $featureFlagFactory;

    parent::__construct();
  }

  /**
   * @inheritDoc
   */
  protected function configure() {
    $this->setDefinition([
      new InputArgument(FeatureFlag::PROPERTY_TYPE, InputArgument::REQUIRED, 'Type of feature flag'),
      new InputOption(FeatureFlag::PROPERTY_NAME, null, InputOption::VALUE_REQUIRED, 'Name of the feature flag'),
      new InputOption(FeatureFlag::PROPERTY_DESCRIPTION, null, InputOption::VALUE_REQUIRED, 'Description of the feature flag'),
    ]);
  }

  /**
   * @inheritDoc
   */
  protected function execute(InputInterface $input, OutputInterface $output): int {
    $io = new SymfonyStyle($input, $output);

    try {
      $featureFlag = $this->featureFlagFactory->updateFromConsole($input, $output);
    } catch (UnableToBuildFeatureFlagException $e) {
      $io->error($e->getMessage());
      return Command::FAILURE;
    } catch (NotFoundException $exception) {
      $io->error(\sprintf('Feature flag %s not found', $exception->getData()['subject'] ?? ''));
      return Command::FAILURE;
    }

    $io->success(\sprintf('Feature flag %s updated', $featureFlag::getType()));

    return Command::SUCCESS;
  }
}
