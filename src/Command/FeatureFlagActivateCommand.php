<?php

namespace HalloVerden\FeatureFlagBundle\Command;

use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;
use HalloVerden\FeatureFlagBundle\Repository\FeatureFlagRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'feature-flag:activate', description: 'Activate feature flag')]
final class FeatureFlagActivateCommand extends Command {
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
  protected function configure(): void {
    $this->setDefinition([
      new InputArgument(FeatureFlag::PROPERTY_TYPE, InputArgument::REQUIRED, 'feature flag type'),
    ]);
  }

  /**
   * @inheritDoc
   */
  protected function execute(InputInterface $input, OutputInterface $output): int {
    $io = new SymfonyStyle($input, $output);

    $featureFlag = $this->featureFlagRepository->updateFeatureFlag(
      $this->featureFlagRepository->getFeatureFlag($input->getArgument(FeatureFlag::PROPERTY_TYPE))->setActive(true)
    );

    $io->success(\sprintf('feature flag %s activated', $featureFlag::getType()));
    return Command::SUCCESS;
  }

}
