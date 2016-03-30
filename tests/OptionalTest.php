<?php
/**
 * php.util.Optional
 *
 * @package PhpOptional
 *
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
 
namespace PhpOptional;

/**
 * @package PhpOptional
 *
 * @author IKEDA Youhei <youhey.ikeda@gmail.com>
 */
class OptionalTest extends \PHPUnit_Framework_TestCase
{
    // \PhpOptional\Optional::of() {{{

    /**
     * @test
     * @dataProvider basicValuesProvider
     */
    public function of($input, $expected)
    {
        $optional = Optional::of($input);
        $this->assertEquals($expected, $optional->get());
    }

    /**
     * @test
     * @expectedException \PhpOptional\NullPointerException
     */
    public function ofTheNullValue()
    {
        $optional = Optional::of(null);
        $this->assertEquals($expected, $optional->get());
    }

    // }}}

    // \PhpOptional\Optional::ofNullable() {{{

    /**
     * @test
     * @dataProvider basicValuesProvider
     */
    public function ofNullable($input, $expected)
    {
        $optional = Optional::ofNullable($input);
        $this->assertEquals($expected, $optional->get());
    }

    /**
     * @test
     */
    public function ofNullableTheNullValue()
    {
        $optional = Optional::ofNullable(null);
        $this->assertFalse($optional->isPresent());
        $this->assertTrue($optional->equals(null));
    }

    // }}}

    // \PhpOptional\Optional::void() {{{

    /**
     * @test
     */
    public function void()
    {
        $optional = Optional::void();
        $this->assertFalse($optional->isPresent());
        $this->assertTrue($optional->equals(null));
    }

    // }}}

    // \PhpOptional\Optional #equals() {{{

    /**
     * @test
     */
    public function equals()
    {
        $obj1 = new \stdClass();
        $obj2 = new \stdClass();

        $optional = Optional::of($obj1);
        $this->assertTrue($optional->equals($obj1));
        $this->assertFalse($optional->equals($obj2));

        $optional_int = Optional::of(42);
        $this->assertTrue($optional_int->equals(42));
        $this->assertFalse($optional_int->equals(100));

        $optional_double = Optional::of(3.14159);
        $this->assertTrue($optional_double->equals(3.14159));
        $this->assertFalse($optional_double->equals(3));

        $optional_string = Optional::of('foo');
        $this->assertTrue($optional_string->equals('foo'));
        $this->assertFalse($optional_string->equals('bar'));
    }

    /**
     * @test
     */
    public function equalsIsIdentity()
    {
        $obj1 = new \stdClass();
        $obj2 = new \stdClass();

        $optional = Optional::of($obj1);
        $this->assertTrue($optional->equals($obj1));
        $this->assertFalse($optional->equals($obj2));

        $optional_int = Optional::of(42);
        $this->assertFalse($optional_int->equals('42'));

        $optional_double = Optional::of(3.14159);
        $this->assertTrue($optional_double->equals(3.14159));
        $this->assertFalse($optional_double->equals(3.141592));

        $empty_optional = Optional::void();
        $this->assertFalse($empty_optional->equals(''));
        $this->assertFalse($empty_optional->equals(0));
        $this->assertFalse($empty_optional->equals(false));
    }

    /**
     * @test
     */
    public function equalsTheOptional()
    {
        $obj = new \stdClass();

        $optional = Optional::of($obj);
        $this->assertTrue($optional->equals(Optional::of($obj)));
        $this->assertFalse($optional->equals(Optional::of(new \stdClass)));

        $optional_int = Optional::of(42);
        $this->assertTrue($optional_int->equals(Optional::of(42)));
        $this->assertFalse($optional_int->equals(Optional::of(100)));

        $empty_optional = Optional::void();
        $this->assertTrue($empty_optional->equals(Optional::void()));
    }

    /**
     * @test
     */
    public function equalsTheClosureValue()
    {
        $optional_int = Optional::of(42);
        $this->assertFalse($optional_int->equals(function () {
            return 42;
        }));

        $func = function ($x) {
            return 42 * $x;
        };

        $optional_func = Optional::of($func);
        $this->assertTrue($optional_func->equals($func));

        $this->assertFalse($optional_func->equals(function ($x) {
            return 42 * $x;
        }));
    }

    // }}}

    // \PhpOptional\Optional #isPresent() {{{

    /**
     * @test
     */
    public function isPresent()
    {
        $optional_int = Optional::of(0);
        $this->assertTrue($optional_int->isPresent());

        $optional_bool = Optional::of(false);
        $this->assertTrue($optional_bool->isPresent());

        $optional_string = Optional::of('');
        $this->assertTrue($optional_string->isPresent());

        $empty_optional = Optional::void();
        $this->assertFalse($empty_optional->isPresent());
    }

    // }}}

    // \PhpOptional\Optional #ifPresent() {{{

    /**
     * @test
     */
    public function ifPresent()
    {
        $optional_string = Optional::of('foo');
        $optional_string->ifPresent(function ($value) {
            echo $value;
        });
        $this->expectOutputString('foo');

        $optional_int = Optional::of(42);
        $result = null;
        $optional_int->ifPresent(function ($value) use (&$result) {
            $result = ($value * 2);
        });
        $this->assertEquals(84, $result);
    }

    /**
     * @test
     */
    public function ifPresentTheEmptyValue()
    {
        $empty_optional = Optional::void();
        $empty_optional->ifPresent(function () {
            echo 'hoge';
        });

        $this->expectOutputString('');
    }

    // }}}

    // \PhpOptional\Optional #get() {{{

    /**
     * @test
     */
    public function get()
    {
        $obj = new \stdClass();
        $optional = Optional::of($obj);
        $this->assertSame($obj, $optional->get());

        $optional_int = Optional::of(42);
        $this->assertSame(42, $optional_int->get());
    }

    /**
     * @test
     * @expectedException \PhpOptional\NoSuchElementException
     */
    public function getTheNullValue()
    {
        $empty_optional = Optional::void();
        $result = $empty_optional->get();
    }

    // }}}

    // \PhpOptional\Optional #orElse() {{{

    /**
     * @test
     */
    public function orElse()
    {
        $optional_int = Optional::of(42);
        $this->assertEquals(42, $optional_int->orElse(100));

        $empty_optional = Optional::void();
        $this->assertEquals(100, $empty_optional->orElse(100));
    }

    // }}}


    // \PhpOptional\Optional #orElseThrow() {{{

    /**
     * @test
     */
    public function orElseThrowTheValueExists()
    {
        $optional_int = Optional::of(42);
        $this->assertEquals(42, $optional_int->orElseThrow('\\BadFunctionCallException'));
    }

    /**
     * @test
     * @expectedException \BadFunctionCallException
     */
    public function orElseThrowTheEmptyValue()
    {
        $empty_optional = Optional::void();
        $result = $empty_optional->orElseThrow('\\BadFunctionCallException');
    }

    // }}}

    // provider {{{

    public static function basicValuesProvider()
    {
        $obj = new \stdClass();
        $obj->MyInt = 99;
        $obj->MyFloat = 123.45;
        $obj->MyBool = true;
        $obj->MyNull = null;
        $obj->MyString = 'Hello World';

        return [
                // integers
                [0, 0],
                [123, 123],
                [-123, -123],
                [2147483647, 2147483647],
                [-2147483648, -2147483648],

                // floats
                [123.456,  123.456],
                [1.23E3, 1.23E3],
                [-1.23E3, -1.23E3],

                // boolean
                [true,  true],
                [false, false],

                // strings
                ['abc', 'abc'],
                ["Hello\t\tWorld\n", "Hello\t\tWorld\n"],

                // arrays
                [[], []],
                [[1, 2, 3, 4, 5], [1, 2, 3, 4, 5]],
                [["Jan" => 31,
                  "Feb" => 29,
                  "Mar" => 31,
                  "April" => 30,
                  "May" => 31,
                  "June" => 30],
                  ["Jan" => 31,
                  "Feb" => 29,
                  "Mar" => 31,
                  "April" => 30,
                  "May" => 31,
                  "June" => 30]],

                // empty data
                ['', ''],

                // object variable
                [$obj , $obj],
            ];
    }

    // }}}
}

// vim:set foldmethod=marker:
