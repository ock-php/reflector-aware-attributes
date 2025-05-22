# Reflector-aware attributes

Provides mechanisms for attribute objects to know about the symbol they are attached to.

## Usage

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
