<?php

use Greabock\Maker\Maker;
use Illuminate\Container\Container;

class MakerConcreteStub
{
}
interface IMakerContractStub
{
}
class MakerImplementationStub implements IMakerContractStub
{
}
class MakerImplementationStubTwo implements IMakerContractStub
{
}

class MakerNestedDependentStub
{
    public $inner;
    public function __construct(ContainerDependentStub $inner)
    {
        $this->inner = $inner;
    }
}

class ContainerDependentStub
{
    public $impl;
    public function __construct(IMakerContractStub $impl)
    {
        $this->impl = $impl;
    }
}

class MakerDefaultValueStub
{
    public $stub;
    public $default;
    public function __construct(MakerConcreteStub $stub, $default = 'some')
    {
        $this->stub = $stub;
        $this->default = $default;
    }
}


class MakerMixedPrimitiveStub
{
    public $first;
    public $last;
    public $stub;
    public function __construct($first, MakerConcreteStub $stub, $last)
    {
        $this->stub = $stub;
        $this->last = $last;
        $this->first = $first;
    }
}

class GreabockMakerTest extends PHPUnit_Framework_TestCase
{
    public function testParametersCanBePassedThroughToClosure()
    {
        $maker = new Maker(new Container());

        $maker->bind('foo', function ($c, $parameters) {
            return $parameters;
        });

        $this->assertEquals([1, 2, 3], $maker->make('foo', [1, 2, 3]));
    }

    public function testParametersCanOverrideDependencies()
    {
        $maker = new Maker(new Container());
        $stub = new ContainerDependentStub($mock = $this->createMock('IMakerContractStub'));
        $resolved = $maker->make('MakerNestedDependentStub', [$stub]);
        $this->assertInstanceOf('MakerNestedDependentStub', $resolved);
        $this->assertEquals($mock, $resolved->inner->impl);
    }

    public function testResolutionOfDefaultParameters()
    {
        $maker = new Maker(new Container());
        $instance = $maker->make('MakerDefaultValueStub');
        $this->assertInstanceOf('MakerConcreteStub', $instance->stub);
        $this->assertEquals('some', $instance->default);
    }

    public function testPassingSomePrimitiveParameters()
    {
        $maker = new Maker(new Container());
        $value = $maker->make('MakerMixedPrimitiveStub', ['first' => 'some', 'last' => 'other']);
        $this->assertInstanceOf('MakerMixedPrimitiveStub', $value);
        $this->assertEquals('some', $value->first);
        $this->assertEquals('other', $value->last);
        $this->assertInstanceOf('MakerConcreteStub', $value->stub);

        $maker = new Maker(new Container());
        $value = $maker->make('MakerMixedPrimitiveStub', [0 => 'some', 2 => 'other']);
        $this->assertInstanceOf('MakerMixedPrimitiveStub', $value);
        $this->assertEquals('some', $value->first);
        $this->assertEquals('other', $value->last);
        $this->assertInstanceOf('MakerConcreteStub', $value->stub);
    }


    /**
     * @expectedException Illuminate\Contracts\Container\BindingResolutionException
     * @expectedExceptionMessage Unresolvable dependency resolving [Parameter #0 [ <required> $first ]] in class MakerMixedPrimitiveStub
     */
    public function testInternalClassWithDefaultParameters()
    {
        $maker = new Maker(new Container());
        $maker->make('MakerMixedPrimitiveStub', []);
    }


    /**
     * @expectedException Illuminate\Contracts\Container\BindingResolutionException
     * @expectedExceptionMessage Target [IMakerContractStub] is not instantiable.
     */
    public function testBindingResolutionExceptionMessage()
    {
        $container = new Container;
        $container->make('IMakerContractStub', []);
    }
}