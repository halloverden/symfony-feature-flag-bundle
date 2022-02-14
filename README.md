# HalloVerdenFeatureFlagBundle

Ability to activate and deactivate features dynamically.

Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require halloverden/symfony-feature-flag-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require halloverden/symfony-feature-flag-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    HalloVerden\FeatureFlagBundle\HalloVerdenFeatureFlagBundle::class => ['all' => true],
];
```

Usage
-----

#### Create an entity representing your feature.

```php
<?php

namespace App\Entity\FeatureFlag;

use Doctrine\ORM\Mapping as ORM;
use HalloVerden\FeatureFlagBundle\Entity\FeatureFlag;

/**
 * Class TestFeatureFlag
 *
 * @package App\Entity\FeatureFlag
 *
 * @ORM\Entity()
 */
class TestFeatureFlag extends FeatureFlag {

  /* If you add additonal properties, override setFromConsole */

  /**
   * @inheritDoc
   */
  public static function getType(): string {
    return 'TEST';
  }

}
```

#### Create and run migration for your new FeatureFlag
```shell
bin/console doctrine:migrations:diff
bin/console doctrine:migrations:migrate
```

#### Create the FeatureFlag
```shell
bin/console feature-flag:create TEST
```

#### Activate feature
```shell
bin/console feature-flag:activate TEST
```

#### Deactivate feature
```shell
bin/console feature-flag:dectivate TEST
```

#### Check if feature is activated:
```php
<?php

namespace App\Services;

use App\Entity\FeatureFlag\TestFeatureFlag;
use HalloVerden\FeatureFlagBundle\Services\FeatureFlagServiceInterface;

class SomeService {
  private FeatureFlagServiceInterface $featureFlagService;

  public function __construct(FeatureFlagServiceInterface $featureFlagService) {
    $this->featureFlagService = $featureFlagService;
  }
  
  public function test(): void {
    if (!$this->featureFlagService->isActive(TestFeatureFlag::class)) {
      return; // or throw an exception
    }
    
    // Do the thing that requires this feature to be active.
  }
}
```
