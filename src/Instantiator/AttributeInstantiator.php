<?php

declare(strict_types = 1);

namespace Ock\ReflectorAwareAttributes\Instantiator;

/**
 * Static factories for attribute instantiators.
 */
class AttributeInstantiator {

  /**
   * Creates an instantiator with decorators.
   *
   * @param array<\Closure(AttributeInstantiatorInterface): AttributeInstantiatorInterface> $decorators
   *   Callbacks that decorate the instantiator.
   */
  public static function compose(array $decorators): AttributeInstantiatorInterface {
    $instantiator = new BasicAttributeInstantiator();
    foreach ($decorators as $decorator) {
      $instantiator = $decorator($instantiator);
      assert($instantiator instanceof AttributeInstantiatorInterface);
    }
    return $instantiator;
  }

  /**
   * Creates an instantiator with the default decorators from this package.
   */
  public static function createDefault(): AttributeInstantiatorInterface {
    $instantiator = new BasicAttributeInstantiator();
    $instantiator = new ReflectorProvidingDecorator($instantiator);
    $instantiator = new ReflectorSettingDecorator($instantiator);
    return $instantiator;
  }

}
