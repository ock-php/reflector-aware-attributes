<?php

declare(strict_types = 1);

namespace Ock\ReflectorAwareAttributes;

interface ReflectorAwareAttributeInterface {

  /**
   * Sets the reflector where the attribute was found.
   *
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant $reflector
   *   The place where the attribute was found.
   *
   * @throws \LogicException
   *   The attribute is not allowed here.
   *   There is no point in catching this, the developer must fix their program.
   */
  public function setReflector(\ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant $reflector): void;

}
