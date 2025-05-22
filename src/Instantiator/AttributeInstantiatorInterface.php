<?php

declare(strict_types = 1);

namespace Ock\ReflectorAwareAttributes\Instantiator;

interface AttributeInstantiatorInterface {

  /**
   * @template T of object
   *
   * @param \ReflectionAttribute<T> $attribute
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant $reflector
   *
   * @return object&T
   */
  public function newInstance(
    \ReflectionAttribute $attribute,
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant $reflector,
  ): object;

}
