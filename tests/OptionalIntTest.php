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
 *
 * @SuppressWarnings(PHPMD)
 */
class OptionalIntTest extends \PHPUnit_Framework_TestCase
{
    // \PhpOptional\OptionalInt #of() {{{

    /**
     * @test
     */
    public function of()
    {
        $optional = OptionalInt::of(42);
        $this->assertEquals(42, $optional->get());

        $optional_zero = OptionalInt::of(0);
        $this->assertEquals(0, $optional_zero->get());
    }

    /**
     * @test
     * @expectedException \TypeError
     */
    public function ofTheStringValue()
    {
        OptionalInt::of('hoge');
    }

    /**
     * @test
     * @expectedException \TypeError
     */
    public function ofTheNull()
    {
        OptionalInt::of(null);
    }

    // }}}

    // \PhpOptional\OptionalInt #void() {{{

    /**
     * @test
     */
    public function void()
    {
        $optional = OptionalInt::void();
        $this->assertFalse($optional->isPresent());
    }

    /**
     * @test
     */
    public function voidIsSingleton()
    {
        $optional1 = OptionalInt::void();
        $optional2 = OptionalInt::void();
        $this->assertSame($optional1, $optional2);
    }

    // }}}

    // \PhpOptional\OptionalInt #equals() {{{

    /**
     * @test
     */
    public function equals()
    {
        $optional = OptionalInt::of(42);
        $this->assertTrue($optional->equals(42));
        $this->assertFalse($optional->equals(100));
    }

    /**
     * @test
     */
    public function equalsTheOptionalInt()
    {
        $optional = OptionalInt::of(42);
        $this->assertTrue($optional->equals(OptionalInt::of(42)));
        $this->assertFalse($optional->equals(OptionalInt::of(100)));
    }

    /**
     * @test
     */
    public function equalsTheEmptyOptional()
    {
        $empty_optional = OptionalInt::void();
        $this->assertTrue($empty_optional->equals(OptionalInt::void()));
    }

    // }}}

    // \PhpOptional\OptionalInt #isPresent() {{{

    /**
     * @test
     */
    public function isPresent()
    {
        $optional = OptionalInt::of(42);
        $this->assertTrue($optional->isPresent());

        $empty_optional = OptionalInt::void();
        $this->assertFalse($empty_optional->isPresent());
    }

    // }}}

    // \PhpOptional\OptionalInt #ifPresent() {{{

    /**
     * @test
     */
    public function ifPresent()
    {
        $optional = OptionalInt::of(42);
        $result = null;
        $optional->ifPresent(function ($value) use (&$result) {
            $result = ($value * $value);
        });
        $this->assertEquals(1764, $result);
    }

    /**
     * @test
     */
    public function ifPresentTheEmptyValue()
    {
        $empty_optional = OptionalInt::void();
        $empty_optional->ifPresent(function () {
            echo 'hoge';
        });

        $this->expectOutputString('');
    }

    // }}}

    // \PhpOptional\OptionalInt #get() {{{

    /**
     * @test
     */
    public function get()
    {
        $optional = OptionalInt::of(42);
        $this->assertSame(42, $optional->get());
    }

    /**
     * @test
     * @expectedException \PhpOptional\NoSuchElementException
     */
    public function getTheNullValue()
    {
        $empty_optional = OptionalInt::void();
        $empty_optional->get();
    }

    // }}}

    // \PhpOptional\OptionalInt #orElse() {{{

    /**
     * @test
     */
    public function orElse()
    {
        $optional_int = OptionalInt::of(42);
        $this->assertEquals(42, $optional_int->orElse(100));

        $empty_optional = OptionalInt::void();
        $this->assertEquals(100, $empty_optional->orElse(100));
    }

    // }}}

    // \PhpOptional\OptionalInt #orElseThrow() {{{

    /**
     * @test
     */
    public function orElseThrowTheValueExists()
    {
        $optional = OptionalInt::of(42);
        $this->assertEquals(42, $optional->orElseThrow('\\BadFunctionCallException'));
    }

    /**
     * @test
     * @expectedException \BadFunctionCallException
     */
    public function orElseThrowTheEmptyValue()
    {
        $empty_optional = OptionalInt::void();
        $empty_optional->orElseThrow('\\BadFunctionCallException');
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage '\ArrayObject' not implement Throwable.
     */
    public function orElseThrowTheNotThrowable()
    {
        $empty_optional = OptionalInt::void();
        $empty_optional->orElseThrow('\\ArrayObject');
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage 'UnknownException' exception does not exist.
     */
    public function orElseThrowTheExceptionDoesNotExists()
    {
        $empty_optional = OptionalInt::void();
        $empty_optional->orElseThrow('UnknownException');
    }

    // }}}
}

// vim:set foldmethod=marker:
