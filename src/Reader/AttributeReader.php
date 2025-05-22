<?php

declare(strict_types = 1);

namespace Ock\ReflectorAwareAttributes\Reader;

use Ock\ReflectorAwareAttributes\Instantiator\AttributeInstantiator;
use Ock\ReflectorAwareAttributes\Instantiator\AttributeInstantiatorInterface;
use Ock\ReflectorAwareAttributes\Instantiator\BasicAttributeInstantiator;

class AttributeReader implements AttributeReaderInterface {

  /**
   * Static cache for the basic reader.
   */
  private static self $basicInstance;

  /**
   * Static cache for the default reader.
   */
  private static self $defaultInstance;

  public function __construct(
    private AttributeInstantiatorInterface $instantiator,
  ) {}

  /**
   * Creates a reader with no extra behaviors.
   */
  public static function basic(): self {
    return self::$basicInstance ??= new self(new BasicAttributeInstantiator());
  }

  /**
   * Creates a reader with the default extra behaviors from this package.
   */
  public static function default(): self {
    return self::$defaultInstance ??= new self(AttributeInstantiator::createDefault());
  }

  /**
   * @param \Closure(AttributeInstantiatorInterface): AttributeInstantiatorInterface $decorate
   *
   * @return static
   */
  public function withDecoratingInstantiator(\Closure $decorate): static {
    $clone = clone $this;
    $clone->instantiator = $decorate($this->instantiator);
    return $clone;
  }

  #[\Override]
  public function getInstances(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant $reflector,
    string $name,
    int $flags = \ReflectionAttribute::IS_INSTANCEOF,
  ): array {
    $attributes = $reflector->getAttributes($name, $flags);
    return array_map(
      fn (\ReflectionAttribute $attribute) => $this->instantiator->newInstance($attribute, $reflector),
      $attributes,
    );
  }

}
