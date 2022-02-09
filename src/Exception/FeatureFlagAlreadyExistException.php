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
  public function __construct(string $type, \Throwable $previous = null) {
    parent::__construct(\sprintf('Feature flag "%s" already exist', $type), 0, $previous);
  }

}
