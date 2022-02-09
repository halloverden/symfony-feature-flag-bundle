<?php

namespace HalloVerden\FeatureFlagBundle\Services;

use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;
use HalloVerden\FeatureFlagBundle\Repository\FeatureFlagRepositoryInterface;
use HalloVerden\HttpExceptions\NotFoundException;

/**
 * Class FeatureFlagService
 *
 * @package HalloVerden\FeatureFlagBundle\Services
 */
class FeatureFlagService implements FeatureFlagServiceInterface {
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
  public function isActive(string $featureFlagClassOrType): bool {
    try {
      return $this->getFeatureFlag($featureFlagClassOrType)->isActive();
    } catch (NotFoundException $exception) {
      return false;
    }
  }

  /**
   * @inheritDoc
   */
  public function getFeatureFlag(string $featureFlagClassOrType): FeatureFlag {
    return $this->featureFlagRepository->getFeatureFlag($featureFlagClassOrType);
  }

}
