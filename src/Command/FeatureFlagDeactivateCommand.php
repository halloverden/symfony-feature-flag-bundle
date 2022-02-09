<?php

namespace HalloVerden\FeatureFlagBundle\Command;

use HalloVerden\FeatureFlagBundle\Repository\FeatureFlagRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class FeatureFlagDeactivateCommand
 *
 * @package HalloVerden\FeatureFlagBundle\Command
 */
class FeatureFlagDeactivateCommand extends Command {
  protected static $defaultName = 'feature-flag:deactivate';
  protected static $defaultDescription = 'Deactivate feature flag';

  private FeatureFlagRepositoryInterface $featureFlagRepository;

  /**
   * @param FeatureFlagRepositoryInterface $featureFlagRepository
   */
  public function __construct(FeatureFlagRepositoryInterface $featureFlagRepository) {
    $this->featureFlagRepository = $featureFlagRepository;

    parent::__construct();
  }

  /**
   * @inheritDoc
   */
  protected function configure() {
    $this->setDefinition([
      new InputArgument('type', InputArgument::REQUIRED, 'feature flag type'),
    ]);
  }

  /**
   * @inheritDoc
   */
  protected function execute(InputInterface $input, OutputInterface $output): int {
    $io = new SymfonyStyle($input, $output);

    $featureFlag = $this->featureFlagRepository->updateFeatureFlag(
      $this->featureFlagRepository->getFeatureFlag($input->getArgument('type'))->setActive(false)
    );

    $io->success(\sprintf('feature flag %s deactivated', $featureFlag::getType()));
    return Command::SUCCESS;
  }

}