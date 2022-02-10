<?php

namespace HalloVerden\FeatureFlagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HalloVerden\EntityUtilsBundle\Interfaces\GenericEntityInterface;
use HalloVerden\EntityUtilsBundle\Traits\DateTimestampableEntityTrait;
use HalloVerden\EntityUtilsBundle\Traits\PrimaryAndNonPrimaryIdsTrait;
use HalloVerden\FeatureFlagBundle\Exception\UnableToBuildFeatureFlagException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
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

  const PROPERTY_NAME = 'name';
  const PROPERTY_DESCRIPTION = 'description';

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
   * @param InputInterface $input
   * @param SymfonyStyle   $io
   *
   * @return $this
   * @throws UnableToBuildFeatureFlagException
   */
  public function setNameFromConsole(InputInterface $input, SymfonyStyle $io): self {
    $name = $input->getOption(self::PROPERTY_NAME) ?: $io->ask('Name', $this->name ?? null, [static::class, 'validateNotNull']);

    if (null === $name) {
      throw new UnableToBuildFeatureFlagException('Missing name');
    }

    return $this->setName($name);
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
   * @param InputInterface $input
   * @param SymfonyStyle   $io
   *
   * @return $this
   */
  public function setDescriptionFromConsole(InputInterface $input, SymfonyStyle $io): self {
    $description = $input->getOption(self::PROPERTY_DESCRIPTION) ?: $io->ask('Description', $this->name ?? null, [static::class, 'validateNotNull']);

    if (null === $description) {
      throw new UnableToBuildFeatureFlagException('Missing description');
    }

    return $this->setName($description);
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

  /**
   * @param $value
   *
   * @return mixed
   * @throws \Exception
   */
  protected final function validateNotNull($value) {
    if (null === $value) {
      throw new \Exception('Value can not be null');
    }

    return $value;
  }
}
