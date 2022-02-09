<?php

namespace HalloVerden\FeatureFlagBundle\Command;

use HalloVerden\FeatureFlagBundle\Repository\FeatureFlagRepositoryInterface;
use HalloVerden\HttpExceptions\NotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class FeatureFlagDeleteCommand
 *
 * @package HalloVerden\FeatureFlagBundle\Command
 */
class FeatureFlagDeleteCommand extends Command {
  protected static $defaultName = 'feature-flag:delete';
  protected static $defaultDescription = 'delete feature flag';

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
      new InputArgument('type', InputArgument::REQUIRED, 'Type of feature flag'),
    ]);
  }

  /**
   * @inheritDoc
   */
  protected function execute(InputInterface $input, OutputInterface $output): int {
    $io = new SymfonyStyle($input, $output);

    try {
      $featureFlag = $this->featureFlagRepository->deleteFeatureFlag($this->featureFlagRepository->getFeatureFlag($input->getArgument('type')));
    } catch (NotFoundException $exception) {
      $io->error(\sprintf('Feature flag %s not found', $exception->getData()['subject'] ?? ''));
      return Command::FAILURE;
    }

    $io->success(\sprintf('Feature flag %s deleted', $featureFlag::getType()));

    return Command::SUCCESS;
  }


}
