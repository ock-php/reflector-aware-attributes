<?php

declare(strict_types = 1);

namespace Ock\ReflectorAwareAttributes\Tests\Fixtures\Attribute;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_ALL)]
class TestAttribute implements TestAttributeInterface {

  public function __construct(
    public readonly ?string $label = NULL,
  ) {}

}
