<?php

declare(strict_types = 1);

namespace Ock\ReflectorAwareAttributes;

/**
 * Static methods to be called from attribute constructors.
 */
class AttributeConstructor {

  /**
   * Reflector that an attribute is attached to.
   */
  private static \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant|null $reflector = null;

  /**
   * @template TReturn
   *
   * @param \Closure(): TReturn $callback
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant $reflector
   *
   * @return TReturn
   */
  public static function callWithReflector(
    \Closure $callback,
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant $reflector,
  ): mixed {
    try {
      self::$reflector = $reflector;
      return $callback();
    }
    finally {
      self::$reflector = null;
    }
  }

  /**
   * Gets the reflector the current attribute is attached to.
   *
   * This should only be called from an attribute constructor that was invoked
   * with ::newAttributeInstance() above.
   *
   * @throws \LogicException
   *   The method is called at the wrong time.
   */
  public static function getReflector(): \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant {
    if (self::$reflector === null) {
      throw new \LogicException("This method can only be called from an attribute constructor, and only when the attribute is instantiated using the 'ock/reflector-aware-attributes' package.");
    }
    return self::$reflector;
  }

  /**
   * Gets the reflector the current attribute is attached to, or NULL.
   *
   * This should only be called from an attribute constructor that was invoked
   * with ::newAttributeInstance() above.
   */
  public static function getReflectorIfSet(): \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant|null {
    return self::$reflector;
  }

}
