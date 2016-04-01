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
class OptionalFloatTest extends \PHPUnit_Framework_TestCase
{
    // \PhpOptional\OptionalFloat #of() {{{

    /**
     * @test
     */
    public function of()
    {
        $optional = OptionalFloat::of(3.14);
        $this->assertEquals(3.14, $optional->get());

        $optional_zero = OptionalFloat::of(0.0);
        $this->assertEquals(0.0, $optional_zero->get());

        $optional_inf = OptionalFloat::of(INF);
        $this->assertEquals(INF, $optional_inf->get());
    }

    /**
     * @test
     * @expectedException \TypeError
     */
    public function ofTheStringValue()
    {
        OptionalFloat::of('hoge');
    }

    /**
     * @test
     * @expectedException \TypeError
     */
    public function ofTheNull()
    {
        OptionalFloat::of(null);
    }

    // }}}

    // \PhpOptional\OptionalFloat #void() {{{

    /**
     * @test
     */
    public function void()
    {
        $optional = OptionalFloat::void();
        $this->assertFalse($optional->isPresent());
    }

    /**
     * @test
     */
    public function voidIsSingleton()
    {
        $optional1 = OptionalFloat::void();
        $optional2 = OptionalFloat::void();
        $this->assertSame($optional1, $optional2);
    }

    // }}}

    // \PhpOptional\OptionalFloat #equals() {{{

    /**
     * @test
     */
    public function equals()
    {
        $optional = OptionalFloat::of(3.14159);
        $this->assertTrue($optional->equals(3.14159));
        $this->assertFalse($optional->equals(42.24));
    }

    /**
     * @test
     */
    public function equalsIsIdentity()
    {
        $optional = OptionalFloat::of(3.14159);
        $this->assertTrue($optional->equals(3.14159));
        $this->assertFalse($optional->equals(3.14));
    }

    /**
     * @test
     */
    public function equalsTheOptionalFloat()
    {
        $optional = OptionalFloat::of(3.14);
        $this->assertTrue($optional->equals(OptionalFloat::of(3.14)));
        $this->assertFalse($optional->equals(OptionalFloat::of(42.24)));
    }

    /**
     * @test
     */
    public function equalsTheEmptyOptional()
    {
        $empty_optional = OptionalFloat::void();
        $this->assertTrue($empty_optional->equals(OptionalFloat::void()));
    }

    // }}}

    // \PhpOptional\OptionalFloat #isPresent() {{{

    /**
     * @test
     */
    public function isPresent()
    {
        $optional = OptionalFloat::of(3.14);
        $this->assertTrue($optional->isPresent());

        $empty_optional = OptionalFloat::void();
        $this->assertFalse($empty_optional->isPresent());
    }

    // }}}

    // \PhpOptional\OptionalFloat #ifPresent() {{{

    /**
     * @test
     */
    public function ifPresent()
    {
        $optional = OptionalFloat::of(3.14);
        $result = null;
        $optional->ifPresent(function ($value) use (&$result) {
            $result = ($value + 0.00159);
        });
        $this->assertEquals(3.14159, $result);
    }

    /**
     * @test
     */
    public function ifPresentTheEmptyValue()
    {
        $empty_optional = OptionalFloat::void();
        $empty_optional->ifPresent(function () {
            echo 'hoge';
        });

        $this->expectOutputString('');
    }

    // }}}

    // \PhpOptional\OptionalFloat #get() {{{

    /**
     * @test
     */
    public function get()
    {
        $optional = OptionalFloat::of(3.14);
        $this->assertSame(3.14, $optional->get());
    }

    /**
     * @test
     * @expectedException \PhpOptional\NoSuchElementException
     */
    public function getTheNullValue()
    {
        $empty_optional = OptionalFloat::void();
        $empty_optional->get();
    }

    // }}}

    // \PhpOptional\OptionalFloat #orElse() {{{

    /**
     * @test
     */
    public function orElse()
    {
        $optional_int = OptionalFloat::of(3.14);
        $this->assertEquals(3.14, $optional_int->orElse(42.24));

        $empty_optional = OptionalFloat::void();
        $this->assertEquals(42.24, $empty_optional->orElse(42.24));
    }

    // }}}

    // \PhpOptional\OptionalFloat #orElseThrow() {{{

    /**
     * @test
     */
    public function orElseThrowTheValueExists()
    {
        $optional = OptionalFloat::of(3.14);
        $this->assertEquals(3.14, $optional->orElseThrow('\\BadFunctionCallException'));
    }

    /**
     * @test
     * @expectedException \BadFunctionCallException
     */
    public function orElseThrowTheEmptyValue()
    {
        $empty_optional = OptionalFloat::void();
        $empty_optional->orElseThrow('\\BadFunctionCallException');
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage '\ArrayObject' not implement Throwable.
     */
    public function orElseThrowTheNotThrowable()
    {
        $empty_optional = OptionalFloat::void();
        $empty_optional->orElseThrow('\\ArrayObject');
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage 'UnknownException' exception does not exist.
     */
    public function orElseThrowTheExceptionDoesNotExists()
    {
        $empty_optional = OptionalFloat::void();
        $empty_optional->orElseThrow('UnknownException');
    }

    // }}}
}

// vim:set foldmethod=marker:
