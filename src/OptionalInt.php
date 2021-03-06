<?php
/**
 * php.util.Optional
 *
 * @package PhpOptional
 *
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */

declare(strict_types=1);

namespace PhpOptional;

/**
 * @package PhpOptional
 *
 * @author IKEDA Youhei <youhey.ikeda@gmail.com>
 */
/**
 * int 値が含まれている場合も含まれていない場合もあるコンテナ・オブジェクト
 *
 * @package PhpOptional
 *
 * @author IKEDA Youhei <youhey.ikeda@gmail.com>
 *
 * @link https://docs.oracle.com/javase/jp/8/docs/api/java/util/OptionalDouble.html java.util.OptionalDouble
 * @link https://docs.oracle.com/javase/jp/8/docs/api/java/util/OptionalLong.html java.util.OptionalLong
 */
class OptionalInt
{
    /**
     * 指定された値を含む OptionalInt インスタンスを返します
     *
     * @param int $value 存在する値
     *
     * @return OptionalInt 値が存在する OptionalInt
     */
    public static function of(int $value): OptionalInt
    {
        return new static($value);
    }

    /** @var OptionalInt empty value instance */
    private static $empty = null;

    /**
     * 空の OptionalInt インスタンスを返します
     *
     * <p>メソッド名 #empty だと phpcs に怒られるので、
     * （たぶん予約語のキーワードにあるから？）近しい単語に……</p>
     *
     * @return OptionalInt 空の OptionalInt
     *
     * @see $empty
     */
    public static function void(): OptionalInt
    {
        if (is_null(self::$empty)) {
            self::$empty = new static(0);
            self::$empty->isPresent = false;
        }

        return self::$empty;
    }

    /** @var bool 存在する値があるか？ */
    private $isPresent;

    /** @var number Optional の値 */
    private $value;

    /** @param int $value OptionalInt の値 */
    private function __construct(int $value)
    {
        $this->isPresent = true;
        $this->value = $value;
    }

    /**
     * 値がこの Optional と等しいかを示します
     *
     * @param mixed $obj 等価性を判定されるオブジェクト
     *
     * @return bool オブジェクトと値が等しければ true、それ以外は false
     */
    public function equals($obj)
    {
        if ($obj instanceof self) {
            return ($this->isPresent() && $obj->isPresent()) ?
                ($this->value === $obj->value) :
                ($this->isPresent() === $obj->isPresent());
        }

        return ($this->isPresent() && ($this->value === $obj));
    }

    /**
     * 存在する値がある場合は true を返し、それ以外の場合は false を返します
     *
     * @return bool 存在する値がある場合はtrueを返し、それ以外の場合はfalseを返します
     */
    public function isPresent(): bool
    {
        return $this->isPresent;
    }

    /**
     * 値が存在する場合はクロージャを実行し、それ以外の場合は何もしない
     *
     * @param \Closure $func 値が存在する場合に実行する処理
     *
     * @return void
     *
     * @see isPresent()
     * @see get()
     */
    public function ifPresent(\Closure $func)
    {
        if ($this->isPresent()) {
            $func->__invoke($this->get());
        }
    }

    /**
     * 値が存在する場合は、その値を返し、
     * それ以外の場合は、NoSuchElementExceptionをスローします
     *
     * @return int 保持する値
     *
     * @throws NoSuchElementException 存在する値がない場合
     *
     * @see isPresent()
     */
    public function get(): int
    {
        if ($this->isPresent()) {
            return $this->value;
        }

        throw new NoSuchElementException('No value present');
    }

    /**
     * 存在する場合は値を返し、それ以外の場合は $other を返します
     *
     * @param int $other 値が存在しない場合は、この値が返される
     *
     * @return int 存在すれば保持する値、それ以外の場合は $other
     *
     * @see isPresent()
     * @see get()
     */
    public function orElse(int $other): int
    {
        return $this->isPresent() ? $this->get() : $other;
    }

    /**
     * 値が存在する場合は、その含まれている値を返し、
     * それ以外の場合は、指定されたサプライヤによって作成された例外をスローします
     *
     * @param string $exception 存在する値がない場合にスローするクラス名
     *
     * @return int 保持する値
     *
     * @throws $exception 存在する値がない場合
     * @throws \RuntimeException $exception 指定した例外クラスが存在しない場合
     *
     * @see isPresent()
     * @see get()
     */
    public function orElseThrow(string $exception): int
    {
        if ($this->isPresent()) {
            return $this->get();
        }

        if (!class_exists($exception)) {
            throw new \RuntimeException("'{$exception}' exception does not exist.");
        }

        $e = new $exception;
        if (!($e instanceof \Throwable)) {
            throw new \RuntimeException("'{$exception}' not implement Throwable.");
        }
        throw $e;
    }
}

// vim:set foldmethod=marker:
