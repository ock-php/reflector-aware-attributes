<?php

declare(strict_types = 1);

namespace Ock\ReflectorAwareAttributes\Instantiator;

/**
 * Basic attribute instantiator without any additional functionality.
 */
class BasicAttributeInstantiator implements AttributeInstantiatorInterface {

  #[\Override]
  public function newInstance(
    \ReflectionAttribute $attribute,
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant $reflector,
  ): object {
    return $attribute->newInstance();
  }

}
