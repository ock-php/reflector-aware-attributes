<?php

declare(strict_types = 1);

namespace Ock\ReflectorAwareAttributes\Tests\Fixtures;

use Ock\ReflectorAwareAttributes\Tests\Fixtures\Attribute\OtherTestAttribute;
use Ock\ReflectorAwareAttributes\Tests\Fixtures\Attribute\ReflectorAwareTestAttribute;
use Ock\ReflectorAwareAttributes\Tests\Fixtures\Attribute\TestAttribute;

#[TestAttribute('on a class')]
#[OtherTestAttribute('on a class')]
#[ReflectorAwareTestAttribute]
class TestClassWithAttributes {

  #[TestAttribute('on a class constant')]
  #[TestAttribute('another one on a class constant')]
  public const SOME_CONST = 5;

  #[TestAttribute('on a property')]
  public int $x = 5;

  #[TestAttribute('on a method')]
  public function foo(
    #[TestAttribute('on a parameter')]
    int $x,
  ): void {}

}
