<?php

/**
 * @file
 * Functions related to attributes.
 */

namespace Ock\ReflectorAwareAttributes;

/**
 * Gets attribute instances, and calls ->setReflector().
 *
 * This is a shortcut to skip dealing with ReflectionAttribute objects.
 * It also provides better support for static analysis.
 *
 * This version will call ->setReflector() on each instance, if that instance
 * implements ReflectorAwareAttributeInterface.
 *
 * @template TAttribute of object
 *
 * @param class-string<TAttribute> $name
 *   Attribute class or interface to filter by.
 * @param int $flags
 *   Flags to pass to ->getAttributes().
 *   Pass 0 to only get instances with the exact class.
 *
 * @return list<TAttribute>
 *   Attribute instances.
 */
function get_attributes(
  \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant $reflector,
  string $name,
  int $flags = \ReflectionAttribute::IS_INSTANCEOF,
): array {
  $instances = AttributeConstructor::callWithReflector(
    fn () => get_raw_attributes($reflector, $name, $flags),
    $reflector,
  );
  foreach ($instances as $instance) {
    if ($instance instanceof ReflectorAwareAttributeInterface) {
      $instance->setReflector($reflector);
    }
  }
  return $instances;
}

/**
 * Gets attribute instances from a reflector.
 *
 * This is a shortcut to skip dealing with ReflectionAttribute objects.
 * It also provides better support for static analysis.
 *
 * This version does _not_ call ->setReflector() on each instance.
 *
 * @template TAttribute of object
 *
 * @param class-string<TAttribute> $name
 *   Attribute class or interface to filter by.
 * @param int $flags
 *   Flags to pass to ->getAttributes().
 *   Pass 0 to only get instances with the exact class.
 *
 * @return list<TAttribute>
 *   Attribute instances.
 */
function get_raw_attributes(
  \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant $reflector,
  string $name,
  int $flags = \ReflectionAttribute::IS_INSTANCEOF,
): array {
  $attributes = $reflector->getAttributes($name, $flags);
  return array_map(fn (\ReflectionAttribute $attribute) => $attribute->newInstance(), $attributes);
}
