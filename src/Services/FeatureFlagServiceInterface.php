<?php

namespace HalloVerden\FeatureFlagBundle\Services;

use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;
use HalloVerden\HttpExceptions\NotFoundException;

interface FeatureFlagServiceInterface {

  /**
   * @param string $featureFlagClassOrType
   *
   * @return bool
   */
  public function isActive(string $featureFlagClassOrType): bool;

  /**
   * @param string $featureFlagClassOrType
   *
   * @return FeatureFlag
   * @throws NotFoundException
   */
  public function getFeatureFlag(string $featureFlagClassOrType): FeatureFlag;

}
