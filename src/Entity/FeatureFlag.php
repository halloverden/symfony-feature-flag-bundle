<?php

namespace HalloVerden\FeatureFlagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HalloVerden\EntityUtilsBundle\Interfaces\GenericEntityInterface;
use HalloVerden\EntityUtilsBundle\Traits\DateTimestampableEntityTrait;
use HalloVerden\EntityUtilsBundle\Traits\PrimaryAndNonPrimaryIdsTrait;
use HalloVerden\FeatureFlagBundle\Exception\UnableToBuildFeatureFlagException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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

  const PROPERTY_TYPE = 'type';
  const PROPERTY_NAME = 'name';
  const PROPERTY_DESCRIPTION = 'description';
  const PROPERTY_ACTIVE = 'active';

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
   * Use setters or createFromConsole to build instance.
   */
  public final function __construct() {
  }

  /**
   * @param InputInterface  $input
   * @param OutputInterface $output
   *
   * @return static
   * @throws UnableToBuildFeatureFlagException
   */
  public static function createFromConsole(InputInterface $input, OutputInterface $output): self {
    return (new static())->setFromConsole($input, $output);
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
    $name = $input->getOption(self::PROPERTY_NAME)
      ?: $io->ask('Name', $this->name ?? null, $this->getValidateNotNullCallable());

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
   * @throws UnableToBuildFeatureFlagException
   */
  public function setDescriptionFromConsole(InputInterface $input, SymfonyStyle $io): self {
    $description = $input->getOption(self::PROPERTY_DESCRIPTION)
      ?: $io->ask('Description', $this->description ?? null, $this->getValidateNotNullCallable());

    if (null === $description) {
      throw new UnableToBuildFeatureFlagException('Missing description');
    }

    return $this->setDescription($description);
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
   * @param InputInterface $input
   * @param SymfonyStyle   $io
   *
   * @return $this
   * @throws UnableToBuildFeatureFlagException
   */
  public function setFromConsole(InputInterface $input, OutputInterface $output): self {
    $io = new SymfonyStyle($input, $output);

    return $this->setNameFromConsole($input, $io)
      ->setDescriptionFromConsole($input, $io);
  }

  /**
   * @return array
   */
  public function toArray(): array {
    return [
      self::PROPERTY_TYPE => $this::getType(),
      self::PROPERTY_NAME => $this->getName(),
      self::PROPERTY_DESCRIPTION => $this->getDescription(),
      self::PROPERTY_ACTIVE => $this->isActive() ? 'yes' : 'no',
    ];
  }

  /**
   * @return callable
   */
  protected final function getValidateNotNullCallable(): callable {
    return function ($value) {
      if (null === $value) {
        throw new \Exception('Value can not be null');
      }

      return $value;
    };
  }
}
