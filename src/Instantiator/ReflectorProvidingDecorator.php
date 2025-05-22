<?php

declare(strict_types = 1);

namespace Ock\ReflectorAwareAttributes\Instantiator;

use Ock\ReflectorAwareAttributes\AttributeConstructor;

/**
 * Decorator that makes the reflector available to the attribute constructor.
 *
 * @see AttributeConstructor::getReflector()
 */
class ReflectorProvidingDecorator implements AttributeInstantiatorInterface {

  public function __construct(
    private readonly AttributeInstantiatorInterface $decorated,
  ) {}

  #[\Override]
  public function newInstance(
    \ReflectionAttribute $attribute,
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant $reflector,
  ): object {
    return AttributeConstructor::callWithReflector(
      fn () => $this->decorated->newInstance($attribute, $reflector),
      $reflector,
    );
  }

}
