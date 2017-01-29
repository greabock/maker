
[![Build Status](https://travis-ci.org/greabock/maker.svg?branch=master)](https://travis-ci.org/greabock/maker)

#Installation
`composer require greabock/maker`  
After updating composer, add the `Greabock\Maker\MakerServiceProvider::class` to the `providers` array in config/app.php

#Usage
```
app(Maker::class)->make(Some::class, ['foo' => 'some', 'bar' => 'other'])
// or
make(Some::class, ['foo' => 'some', 'bar' => 'other']);
```
#Warning!

This function is similiar but isn't full compatible with old `App::make()`. 
Contextual binding does not work when you build objects with Maker.






