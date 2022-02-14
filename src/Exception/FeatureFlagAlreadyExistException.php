<?php

namespace HalloVerden\FeatureFlagBundle\Exception;

/**
 * Class FeatureFlagAlreadyExistException
 *
 * @package HalloVerden\FeatureFlagBundle\Exception
 */
class FeatureFlagAlreadyExistException extends \Exception {

  /**
   * @inheritDoc
   */
  public function __construct(string $typeOrClass, \Throwable $previous = null) {
    parent::__construct(\sprintf('Feature flag "%s" already exist', $typeOrClass), 0, $previous);
  }

}
