<?php

namespace HalloVerden\FeatureFlagBundle\Command;

use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;
use HalloVerden\FeatureFlagBundle\Repository\FeatureFlagRepositoryInterface;
use HalloVerden\HttpExceptions\NotFoundException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'feature-flag:delete', description: 'delete feature flag')]
class FeatureFlagDeleteCommand extends Command {
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
      new InputArgument(FeatureFlag::PROPERTY_TYPE, InputArgument::REQUIRED, 'Type of feature flag'),
    ]);
  }

  /**
   * @inheritDoc
   */
  protected function execute(InputInterface $input, OutputInterface $output): int {
    $io = new SymfonyStyle($input, $output);

    try {
      $featureFlag = $this->featureFlagRepository->deleteFeatureFlag($this->featureFlagRepository->getFeatureFlag($input->getArgument(FeatureFlag::PROPERTY_TYPE)));
    } catch (NotFoundException $exception) {
      $io->error(\sprintf('Feature flag %s not found', $exception->getData()['subject'] ?? ''));
      return Command::FAILURE;
    }

    $io->success(\sprintf('Feature flag %s deleted', $featureFlag::getType()));

    return Command::SUCCESS;
  }


}
