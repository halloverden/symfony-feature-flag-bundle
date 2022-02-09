<?php

namespace HalloVerden\FeatureFlagBundle\Repository;

use Doctrine\Common\Collections\Collection;
use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;

/**
 * Interface FeatureFlagRepositoryInterface
 *
 * @package HalloVerden\FeatureFlagBundle\Repository
 */
interface FeatureFlagRepositoryInterface {

  /**
   * @param string $type
   *
   * @return string
   */
  public function getClass(string $type): string;

  /**
   * @return Collection<FeatureFlag>|FeatureFlag[]
   */
  public function getAll(): Collection;

  /**
   * @param string $typeOrClass
   *
   * @return FeatureFlag
   */
  public function getFeatureFlag(string $typeOrClass): FeatureFlag;

  /**
   * @param FeatureFlag $featureFlag
   *
   * @return FeatureFlag
   */
  public function createFeatureFlag(FeatureFlag $featureFlag): FeatureFlag;

  /**
   * @param FeatureFlag $featureFlag
   *
   * @return FeatureFlag
   */
  public function updateFeatureFlag(FeatureFlag $featureFlag): FeatureFlag;

  /**
   * @param FeatureFlag $featureFlag
   *
   * @return FeatureFlag
   */
  public function deleteFeatureFlag(FeatureFlag $featureFlag): FeatureFlag;

}
