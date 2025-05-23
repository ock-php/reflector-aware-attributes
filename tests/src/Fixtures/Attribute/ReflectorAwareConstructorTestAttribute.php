<?php

declare(strict_types = 1);

namespace Ock\ReflectorAwareAttributes\Tests\Fixtures\Attribute;

use Ock\ReflectorAwareAttributes\AttributeConstructor;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_ALL)]
class ReflectorAwareConstructorTestAttribute {

  public readonly ?\Reflector $reflectorIfSet;

  public readonly ?\Reflector $reflector;

  public readonly ?\LogicException $exception;

  public function __construct(
    public readonly string $name,
  ) {
    $this->reflectorIfSet = AttributeConstructor::getReflectorIfSet();
    try {
      $this->reflector = AttributeConstructor::getReflector();
      $this->exception = NULL;
    }
    catch (\LogicException $e) {
      $this->reflector = NULL;
      $this->exception = $e;
    }
  }

}
