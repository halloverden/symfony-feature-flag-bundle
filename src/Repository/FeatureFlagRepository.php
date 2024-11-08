<?php

namespace HalloVerden\FeatureFlagBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;
use HalloVerden\FeatureFlagBundle\Exception\FeatureFlagClassNotFoundException;
use HalloVerden\HttpExceptions\NoContentException;
use HalloVerden\HttpExceptions\NotFoundException;

/**
 * @method FeatureFlag|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeatureFlag|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeatureFlag[]    findAll()
 * @method FeatureFlag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class FeatureFlagRepository extends ServiceEntityRepository implements FeatureFlagRepositoryInterface {

  /**
   * @param ManagerRegistry $registry
   */
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, FeatureFlag::class);
  }

  /**
   * @inheritDoc
   */
  public function getClass(string $typeOrClass): string {
    if (\is_subclass_of($typeOrClass, FeatureFlag::class, true)) {
      return $typeOrClass;
    }

    $class = $this->getClassMetadata()->discriminatorMap[$typeOrClass] ?? null;

    if (null === $class) {
      throw new FeatureFlagClassNotFoundException($typeOrClass);
    }

    return $class;
  }

  /**
   * @inheritDoc
   */
  public function getFeatureFlags(): Collection {
    $featureFlags = new ArrayCollection($this->findAll());

    if ($featureFlags->isEmpty()) {
      throw new NoContentException();
    }

    return $featureFlags;
  }

  /**
   * @inheritDoc
   * @throws NonUniqueResultException
   */
  public function getFeatureFlag(string $typeOrClass): FeatureFlag {
    $qb = $this->createQueryBuilder('ff');

    $qb->andWhere($qb->expr()->isInstanceOf('ff', $this->getClass($typeOrClass)));

    try {
      return $qb->getQuery()->getSingleResult();
    } catch (NoResultException $e) {
      throw new NotFoundException($typeOrClass);
    }
  }

  /**
   * @inheritDoc
   */
  public function createFeatureFlag(FeatureFlag $featureFlag): FeatureFlag {
    $this->getEntityManager()->persist($featureFlag);
    $this->getEntityManager()->flush();

    return $featureFlag;
  }

  /**
   * @inheritDoc
   */
  public function updateFeatureFlag(FeatureFlag $featureFlag): FeatureFlag {
    $this->getEntityManager()->flush();

    return $featureFlag;
  }

  /**
   * @inheritDoc
   */
  public function deleteFeatureFlag(FeatureFlag $featureFlag): FeatureFlag {
    $this->getEntityManager()->remove($featureFlag);
    $this->getEntityManager()->flush();

    return $featureFlag;
  }

}
