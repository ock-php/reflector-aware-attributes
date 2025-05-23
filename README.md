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
