
[![Build Status](https://travis-ci.org/greabock/maker.svg?branch=master)](https://travis-ci.org/greabock/maker)

# Intention
Laravel 5.4 had severely crippled DI container.
> The container's make method no longer accepts a second array of parameters. This feature typically indicates a code smell. Typically, you can always construct the object in another way that is more intuitive.

This library intends to bring back its former glory.

#Installation
`composer require greabock/maker`  
After updating composer, add the `Greabock\Maker\MakerServiceProvider::class` to the `providers` array in config/app.php

#Usage
```php
app(Maker::class)->make(Some::class, ['foo' => 'some', 'bar' => 'other'])
// or
make(Some::class, ['foo' => 'some', 'bar' => 'other']);
```
You can also bind closure:
```php
use Illuminate\Contracts\Container\Container;
use Greabock\Maker\Maker;

app(Maker::class)->bind(Some::class, function(Container $container, $parameters){
   $some = $container->make(Some::class);
   $some->doSomeThing($parameters);
   return $some;
});
```
#Warning!

This function is similiar but isn't fully compatible with old `App::make()`. 
Contextual binding does not work when you build objects with Maker.






