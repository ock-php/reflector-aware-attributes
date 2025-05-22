<?php

declare(strict_types = 1);

namespace Ock\ReflectorAwareAttributes\Instantiator;

use Ock\ReflectorAwareAttributes\ReflectorAwareAttributeInterface;

/**
 * Decorator that calls ->setReflector() on the attribute instance.
 *
 * @see ReflectorAwareAttributeInterface::setReflector()
 */
class ReflectorSettingDecorator implements AttributeInstantiatorInterface {

  public function __construct(
    private readonly AttributeInstantiatorInterface $decorated,
  ) {}

  #[\Override]
  public function newInstance(
    \ReflectionAttribute $attribute,
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant $reflector,
  ): object {
    $instance = $this->decorated->newInstance($attribute, $reflector);
    if ($instance instanceof ReflectorAwareAttributeInterface) {
      $instance->setReflector($reflector);
    }
    return $instance;
  }

}
