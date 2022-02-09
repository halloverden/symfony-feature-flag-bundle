<?php

namespace HalloVerden\FeatureFlagBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;

/**
 * Class DiscriminatorMapListener
 *
 * @package HalloVerden\FeatureFlagBundle\EventListener
 */
class DiscriminatorMapListener implements EventSubscriber {

  /**
   * @inheritDoc
   */
  public function getSubscribedEvents(): array {
    return [
      Events::loadClassMetadata,
    ];
  }

  /**
   * @param LoadClassMetadataEventArgs $eventArgs
   *
   * @return void
   */
  public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void {
    $classMetadata = $eventArgs->getClassMetadata();

    if ($classMetadata->getName() !== FeatureFlag::class) {
      return;
    }

    $map = [];
    foreach ($classMetadata->discriminatorMap as $class) {
      if ($type = $this->getType($class)) {
        $map[$type] = $class;
      }
    }

    $classMetadata->discriminatorMap = [];
    $classMetadata->setDiscriminatorMap($map);
  }

  /**
   * @param string $class
   *
   * @return string|null
   */
  private function getType(string $class): ?string {
    // Abstract class should not have a map.
    if ($class === FeatureFlag::class) {
      return null;
    }

    if (!\is_subclass_of($class, FeatureFlag::class, true)) {
      throw new \LogicException(\sprintf('%s is not a subclass of %s', $class, FeatureFlag::class));
    }

    return $class::getType();
  }

}
