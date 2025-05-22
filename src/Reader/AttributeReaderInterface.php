<?php

/**
 * @file
 */

declare(strict_types = 1);

namespace Ock\ReflectorAwareAttributes\Reader;

interface AttributeReaderInterface {

  /**
   * @template T of object
   *
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant $reflector
   * @param class-string<T> $name
   * @param int $flags
   *
   * @return list<T&object>
   */
  public function getInstances(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant $reflector,
    string $name,
    int $flags = \ReflectionAttribute::IS_INSTANCEOF,
  ): array;

}
