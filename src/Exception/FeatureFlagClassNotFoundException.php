<?php

namespace HalloVerden\FeatureFlagBundle\Exception;


final class FeatureFlagClassNotFoundException extends \RuntimeException {

  /**
   * @inheritDoc
   */
  public function __construct(string $type, \Throwable $previous = null) {
    parent::__construct(\sprintf('Feature flag class for type "%s" not found', $type), 0, $previous);
  }

}
