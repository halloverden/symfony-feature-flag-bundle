<?php

namespace HalloVerden\FeatureFlagBundle\Command;

use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;
use HalloVerden\FeatureFlagBundle\Exception\FeatureFlagAlreadyExistException;
use HalloVerden\FeatureFlagBundle\Exception\UnableToBuildFeatureFlagException;
use HalloVerden\FeatureFlagBundle\Factory\FeatureFlagFactoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'feature-flag:create', description: 'Create feature flag')]
final class FeatureFlagCreateCommand extends Command {
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
  protected function configure(): void {
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
      $featureFlag = $this->featureFlagFactory->createFromConsole($input, $output);
    } catch (UnableToBuildFeatureFlagException|FeatureFlagAlreadyExistException $e) {
      $io->error($e->getMessage());
      return Command::FAILURE;
    }

    $io->success(\sprintf('Feature flag %s created, use "%s %s" to enable', $featureFlag::getType(), FeatureFlagActivateCommand::getDefaultName(), $featureFlag::getType()));

    return Command::SUCCESS;
  }


}
