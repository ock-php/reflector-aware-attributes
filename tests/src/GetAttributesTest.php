<?php

declare(strict_types=1);

namespace Ock\ReflectorAwareAttributes\Tests;

use Ock\ReflectorAwareAttributes\Tests\Fixtures\Attribute\OtherTestAttribute;
use Ock\ReflectorAwareAttributes\Tests\Fixtures\Attribute\ReflectorAwareConstructorTestAttribute;
use Ock\ReflectorAwareAttributes\Tests\Fixtures\Attribute\ReflectorAwareTestAttribute;
use Ock\ReflectorAwareAttributes\Tests\Fixtures\Attribute\TestAttribute;
use Ock\ReflectorAwareAttributes\Tests\Fixtures\Attribute\TestAttributeInterface;
use Ock\ReflectorAwareAttributes\Tests\Fixtures\TestClassWithAttributes;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function Ock\ReflectorAwareAttributes\get_attributes;
use function Ock\ReflectorAwareAttributes\get_raw_attributes;

/**
 * @covers \Ock\ReflectorAwareAttributes\get_attributes()
 * @covers \Ock\ReflectorAwareAttributes\get_raw_attributes()
 */
class GetAttributesTest extends TestCase {

  /**
   * @param \Closure(\ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant, class-string, int=): list<object> $get_attributes
   */
  #[DataProvider('providerGetAttributesFunction')]
  public function testOnClass(\Closure $get_attributes): void {
    $reflector = new \ReflectionClass(TestClassWithAttributes::class);
    $attributes = $get_attributes($reflector, TestAttribute::class);
    $this->assertEquals([new TestAttribute('on a class')], $attributes);
  }

  public function testSetReflector(): void {
    $reflector = new \ReflectionClass(TestClassWithAttributes::class);
    $attributes = get_attributes($reflector, ReflectorAwareTestAttribute::class);
    $this->assertSame($reflector, $attributes[0]->reflector);
  }

  public function testNotSetReflector(): void {
    $reflector = new \ReflectionClass(TestClassWithAttributes::class);
    $attributes = get_raw_attributes($reflector, ReflectorAwareTestAttribute::class);
    $this->assertNull($attributes[0]->reflector);
  }

  public function testGetReflectorFromConstructor(): void {
    $reflector = new \ReflectionClass(TestClassWithAttributes::class);
    [$attribute] = get_attributes($reflector, ReflectorAwareConstructorTestAttribute::class);
    $this->assertSame($reflector, $attribute->reflectorIfSet);
    $this->assertSame($reflector, $attribute->reflector);
    $this->assertNull($attribute->exception ?? NULL);
  }

  public function testGetNoReflectorFromConstructor(): void {
    $reflector = new \ReflectionClass(TestClassWithAttributes::class);
    [$raw_attribute] = get_raw_attributes($reflector, ReflectorAwareConstructorTestAttribute::class);
    $this->assertNull($raw_attribute->reflectorIfSet);
    $this->assertNull($raw_attribute->reflector ?? NULL);
    $this->assertNotNull($raw_attribute->exception);
    $this->assertSame(\LogicException::class, get_class($raw_attribute->exception));
  }

  /**
   * @param \Closure(\ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant, class-string, int=): list<object> $get_attributes
   */
  #[DataProvider('providerGetAttributesFunction')]
  public function testByType(\Closure $get_attributes): void {
    $reflector = new \ReflectionClass(TestClassWithAttributes::class);

    $attributes = $get_attributes($reflector, TestAttributeInterface::class);
    $this->assertEquals([
      new TestAttribute('on a class'),
      new OtherTestAttribute('on a class'),
    ], $attributes);

    $attributes = $get_attributes($reflector, OtherTestAttribute::class);
    $this->assertEquals([
      // The array index starts at 0, even if an earlier attribute was omitted.
      new OtherTestAttribute('on a class'),
    ], $attributes);

    $attributes = $get_attributes($reflector, \stdClass::class);
    $this->assertEquals([], $attributes);

    $attributes = $get_attributes($reflector, TestAttributeInterface::class, 0);
    $this->assertEquals([], $attributes);
  }

  /**
   * @param \Closure(\ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant, class-string, int=): list<object> $get_attributes
   */
  #[DataProvider('providerGetAttributesFunction')]
  public function testOnDifferentReflectors(\Closure $get_attributes): void {
    $function = #[TestAttribute('on anonymous function')] fn() => NULL;
    $reflector = new \ReflectionFunction($function);
    $attributes = $get_attributes($reflector, TestAttribute::class);
    $this->assertEquals([new TestAttribute('on anonymous function')], $attributes);

    $reflector = new \ReflectionMethod(TestClassWithAttributes::class, 'foo');
    $attributes = $get_attributes($reflector, TestAttribute::class);
    $this->assertEquals([new TestAttribute('on a method')], $attributes);

    $reflector = $reflector->getParameters()[0];
    $attributes = $get_attributes($reflector, TestAttribute::class);
    $this->assertEquals([new TestAttribute('on a parameter')], $attributes);

    $reflector = new \ReflectionProperty(TestClassWithAttributes::class, 'x');
    $attributes = $get_attributes($reflector, TestAttribute::class);
    $this->assertEquals([new TestAttribute('on a property')], $attributes);

    $reflector = new \ReflectionClassConstant(TestClassWithAttributes::class, 'SOME_CONST');
    $attributes = $get_attributes($reflector, TestAttribute::class);
    $this->assertEquals([
      new TestAttribute('on a class constant'),
      new TestAttribute('another one on a class constant'),
    ], $attributes);
  }

  /**
   * @return array<string, array{\Closure(\ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant, class-string, int=): list<object>}>
   */
  public static function providerGetAttributesFunction(): array {
    return [
      'get_attributes' => [get_attributes(...)],
      'get_raw_attributes' => [get_raw_attributes(...)],
    ];
  }

}
