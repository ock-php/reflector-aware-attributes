<?php

declare(strict_types = 1);

namespace Ock\ReflectorAwareAttributes\Tests\Fixtures\Attribute;

use Ock\ReflectorAwareAttributes\ReflectorAwareAttributeInterface;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_ALL)]
class ReflectorAwareTestAttribute implements ReflectorAwareAttributeInterface {

  public ?\Reflector $reflector = NULL;

  #[\Override]
  public function setReflector(\Reflector $reflector): void {
    $this->reflector = $reflector;
  }

}
