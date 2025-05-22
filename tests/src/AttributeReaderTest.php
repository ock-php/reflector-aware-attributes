<?php

declare(strict_types = 1);

namespace Ock\ReflectorAwareAttributes\Tests;

use Ock\ReflectorAwareAttributes\Instantiator\BasicAttributeInstantiator;
use Ock\ReflectorAwareAttributes\Instantiator\ReflectorProvidingDecorator;
use Ock\ReflectorAwareAttributes\Reader\AttributeReader;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AttributeReader::class)]
class AttributeReaderTest extends TestCase {

  public function testWithDecorator(): void {
    $instantiator = new BasicAttributeInstantiator();
    $reader = new AttributeReader($instantiator);
    $reader = $reader->withDecoratingInstantiator(function ($decorated) use ($instantiator, &$decorating) {
      Assert::assertSame($instantiator, $decorated);
      $decorating = new ReflectorProvidingDecorator($decorated);
      return $decorating;
    });
    $reader = $reader->withDecoratingInstantiator(function ($decorated) use ($decorating) {
      Assert::assertSame($decorating, $decorated);
      return $decorated;
    });
    $this->assertEquals(
      new AttributeReader(
        new ReflectorProvidingDecorator($instantiator),
      ),
      $reader,
    );
  }

}
