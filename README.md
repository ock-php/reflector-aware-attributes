# Reflector-aware attributes

Provides mechanisms for attribute objects to know about the symbol they are attached to.

## Usage

### Using AttributeConstructor::getReflector().

Create an attribute class that calls `AttributeConstructor::getReflector()` in the constructor.

```php
use Ock\ReflectorAwareAttributes\AttributeConstructor;
use Ock\ReflectorAwareAttributes\ReflectorAwareAttributeInterface;

#[\Attribute(\Attribute::TARGET_ALL)]
class MyAttribute {

  public readonly \Reflector $reflector;

  public function __construct(): void {
    $this->reflector = AttributeConstructor::getReflector();
  }

}
```

Attach the attribute to a class or other symbol.

```php
#[MyAttribute]
class MyClass {}
```

Call `get_attributes()` to extract attributes instances from the class.

```php
use function Ock\ReflectorAwareAttributes\get_attributes;

$reflection_class = new \ReflectionClass(MyClass::class);
$attribute_instances = get_attributes($reflection_class, MyAttribute::class);
assert($attribute_instances[0] instanceof MyAttribute);
assert($attribute_instances[0]->reflector === $reflection_function);
```

### Using the interface with ->setReflector().

Create an attribute class that implements `ReflectorAwareAttributeInterface`.

```php
use Ock\ReflectorAwareAttributes\ReflectorAwareAttributeInterface;

#[\Attribute(\Attribute::TARGET_ALL)]
class MyReflectorAwareAttribute implements ReflectorAwareAttributeInterface {

  public readonly \Reflector $reflector;

  public function setReflector(\Reflector $reflector): void {
    $this->reflector = $reflector;
  }

}
```

Attach the attribute to a class or other symbol.

```php
#[MyReflectorAwareAttribute]
function foo() {}
```

Call `get_attributes()` to extract attributes instances from the function.

```php
use function Ock\ReflectorAwareAttributes\get_attributes;

$reflection_function = new \ReflectionFunction('foo');
$attribute_instances = get_attributes($reflection_function, MyReflectorAwareAttribute::class);
assert($attribute_instances[0] instanceof MyReflectorAwareAttribute);
assert($attribute_instances[0]->reflector === $reflection_function);
```

### Using AttributeReader.

The AttributeReader class allows to support attribute types that do not use the mechanisms from this package.

Assume you have an attribute class like this, coming from a 3rd party package:

```php
#[\Attribute(\Attribute::TARGET_CLASS)]
class ThirdPartyAttribute {
  public readonly \Reflector $reflector;
}
```

You can create a custom instantiator that will populate the property.

Ideally this should use the decorator pattern, so that multiple operations can be applied.

```php
use Ock\ReflectorAwareAttributes\Instantiator\AttributeInstantiatorInterface;
use Ock\ReflectorAwareAttributes\Reader\AttributeReader;

class MyInstantiator implements AttributeInstantiatorInterface {

  public function __construct(
    private readonly AttributeInstantiatorInterface $decorated,
  ) {}

  public function newInstance(
    \ReflectionAttribute $attribute,
    \ReflectionClassConstant|\ReflectionParameter|\ReflectionClass|\ReflectionProperty|\ReflectionFunctionAbstract $reflector,
  ): object {
    $instance = $this->decorated->newInstance($attribute, $reflector);
    if ($instance instanceof ThirdPartyAttribute) {
      $instance->reflector = $reflector;
    }
    return $instance;
  }

}
```

Now we can use a reader with this instantiator decorator to get the attribute instances.

```php
#[ThirdPartyAttribute]
class C {}

$reader = AttributeReader::basic()
  ->withDecoratingInstantiator(
    fn (AttributeInstantiatorInterface $decorated) => new MyInstantiator($decorated),
  );

$reflection_class = new \ReflectionClass(C::class);
$instances = $reader->getInstances($reflection_class, ThirdPartyAttribute::class);

assert($instances[0] instanceof ThirdPartyAttribute);
assert($instances[0]->reflector === $reflection_class);
```
