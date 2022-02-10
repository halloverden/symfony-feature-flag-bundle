<?php

namespace HalloVerden\FeatureFlagBundle\Command;

use HalloVerden\FeatureFlagBundle\Repository\FeatureFlagRepositoryInterface;
use HalloVerden\HttpExceptions\NoContentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class FeatureFlagListCommand
 *
 * @package HalloVerden\FeatureFlagBundle\Command
 */
class FeatureFlagListCommand extends Command {
  protected static $defaultName = 'feature-flag:list';
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
      new InputOption('horizontal', null, InputOption::VALUE_NONE, 'Output table horizontal')
    ]);
  }

  /**
   * @inheritDoc
   */
  protected function execute(InputInterface $input, OutputInterface $output): int {
    $io = new SymfonyStyle($input, $output);

    try {
      $featureFlags = $this->featureFlagRepository->getAll();
    } catch (NoContentException $exception) {
      $io->warning('No feature flags created');
      return Command::SUCCESS;
    }

    $table = $io->createTable()->setHeaders(['type', 'name', 'description', 'active']);
    foreach ($featureFlags as $featureFlag) {
      $table->addRow([$featureFlag::getType(), $featureFlag->getName(), $featureFlag->getDescription(), $featureFlag->isActive() ? 'yes' : 'no']);
    }
    $table->setHorizontal($input->getOption('horizontal'));
    $table->render();
    $io->newLine();

    return Command::SUCCESS;
  }


}
