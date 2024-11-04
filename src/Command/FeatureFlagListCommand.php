<?php

namespace HalloVerden\FeatureFlagBundle\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;
use HalloVerden\FeatureFlagBundle\Repository\FeatureFlagRepositoryInterface;
use HalloVerden\HttpExceptions\NoContentException;
use HalloVerden\HttpExceptions\NotFoundException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'feature-flag:list', description: 'delete feature flag')]
final class FeatureFlagListCommand extends Command {
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
      new InputArgument(FeatureFlag::PROPERTY_TYPE, InputArgument::OPTIONAL, 'type of feature flag'),
      new InputOption('horizontal', null, InputOption::VALUE_NONE, 'Outputs table horizontally')
    ]);
  }

  /**
   * @inheritDoc
   */
  protected function execute(InputInterface $input, OutputInterface $output): int {
    $io = new SymfonyStyle($input, $output);

    try {
      $featureFlags = $this->getFeatureFlags($input);
    } catch (NoContentException $exception) {
      $io->warning('No feature flags exist');
      return Command::SUCCESS;
    } catch (NotFoundException $exception) {
      $io->error(\sprintf('Feature flag of type %s not found', $exception->getData()['subject'] ?? ''));
      return Command::FAILURE;
    }

    $headers = [];
    $rows = [];

    foreach ($featureFlags as $featureFlag) {
      $featureFlagAsArray = $featureFlag->toArray();
      $headers = \array_merge($headers, \array_keys($featureFlagAsArray));
      $rows[] = \array_values($featureFlagAsArray);
    }

    $io->createTable()
      ->setHeaders($headers)
      ->setRows($rows)
      ->setHorizontal($input->getOption('horizontal'))
      ->render();
    $io->newLine();

    return Command::SUCCESS;
  }

  /**
   * @param InputInterface $input
   *
   * @return Collection<FeatureFlag>|FeatureFlag[]
   */
  private function getFeatureFlags(InputInterface $input): Collection {
    if ($type = $input->getArgument(FeatureFlag::PROPERTY_TYPE)) {
      return new ArrayCollection([$this->featureFlagRepository->getFeatureFlag($type)]);
    }

    return $this->featureFlagRepository->getFeatureFlags();
  }


}
