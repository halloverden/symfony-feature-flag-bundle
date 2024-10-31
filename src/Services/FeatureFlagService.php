<?php

namespace HalloVerden\FeatureFlagBundle\Services;

use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;
use HalloVerden\FeatureFlagBundle\Repository\FeatureFlagRepositoryInterface;
use HalloVerden\HttpExceptions\NotFoundException;


final readonly class FeatureFlagService implements FeatureFlagServiceInterface {

  /**
   * FeatureFlagService constructor.
   */
  public function __construct(private FeatureFlagRepositoryInterface $featureFlagRepository) {
  }

  /**
   * @inheritDoc
   */
  public function isActive(string $featureFlagClassOrType): bool {
    try {
      return $this->getFeatureFlag($featureFlagClassOrType)->isActive();
    } catch (NotFoundException) {
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
