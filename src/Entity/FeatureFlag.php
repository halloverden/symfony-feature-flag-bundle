<?php

namespace HalloVerden\FeatureFlagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HalloVerden\EntityUtilsBundle\Interfaces\GenericEntityInterface;
use HalloVerden\EntityUtilsBundle\Traits\DateTimestampableEntityTrait;
use HalloVerden\EntityUtilsBundle\Traits\PrimaryAndNonPrimaryIdsTrait;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class FeatureFlag
 *
 * @package HalloVerden\FeatureFlagBundle\Entity
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"type"})})
 * @ORM\Entity()
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 */
abstract class FeatureFlag implements GenericEntityInterface {
  use PrimaryAndNonPrimaryIdsTrait;
  use DateTimestampableEntityTrait;

  /**
   * @var string
   *
   * @ORM\Column(name="name", type="string", nullable=false)
   */
  private string $name;

  /**
   * @var string
   *
   * @ORM\Column(name="description", type="text", nullable=false)
   */
  private string $description;

  /**
   * @var bool
   *
   * @ORM\Column(name="active", type="boolean", nullable=false)
   */
  private bool $active = false;

  /**
   * Use setters to build instance.
   */
  public final function __construct() {
  }

  /**
   * @return string
   */
  public static abstract function getType(): string;

  /**
   * @return string
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * @param string $name
   *
   * @return self
   */
  public function setName(string $name): self {
    $this->name = $name;
    return $this;
  }

  /**
   * @return string
   */
  public function getDescription(): string {
    return $this->description;
  }

  /**
   * @param string $description
   *
   * @return self
   */
  public function setDescription(string $description): self {
    $this->description = $description;
    return $this;
  }

  /**
   * @return bool
   */
  public function isActive(): bool {
    return $this->active;
  }

  /**
   * @param bool $active
   *
   * @return self
   */
  public function setActive(bool $active): self {
    $this->active = $active;
    return $this;
  }

}
