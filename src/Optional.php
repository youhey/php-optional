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
 * null 以外の値が含まれている場合も含まれていない場合もあるコンテナ・オブジェクト
 *
 * <p>JAVA8 の java.util.Optional を見よう見まねでそれっぽく真似してみました。</p>
 *
 * @package PhpOptional
 *
 * @author IKEDA Youhei <youhey.ikeda@gmail.com>
 *
 * @link https://docs.oracle.com/javase/jp/8/docs/api/java/util/Optional.html java.util.Optional
 */
class Optional
{
    /**
     * 非 null 値を含むOptionalを返します
     *
     * @param mixed $value 非 null の存在する値
     *
     * @return Optional 存在する値でのOptional
     *
     * @throws NullPointerException value が null の場合
     */
    public static function of($value)
    {
        if (is_null($value)) {
            throw new NullPointerException;
        }

        return new static($value);
    }

    /**
     * 指定された値がnullでない場合はその値を記述するOptionalを返し、
     * それ以外の場合は空のOptionalを返します
     *
     * @param mixed $value null を含む値
     *
     * @return Optional 指定された値が null でなければ存在する値での Optional
     *                  それ以外の場合は空の Optional
     */
    public static function ofNullable($value)
    {
        return is_null($value) ? static::void() : static::of($value);
    }

    /**
     * 空の Optional インスタンスを返します
     *
     * <p>メソッド名 #empty だと phpcs に怒られるので、
     * （たぶん予約語のキーワードにあるから？）近しい単語に……</p>
     *
     * @return Optional 空の Optional
     */
    public static function void()
    {
        return new static();
    }

    /** @var mixed $value Optional の値 */
    private $value = null;

    /** @param mixed $value Optional の値 */
    private function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * 値がこの Optional と等しいかを示します
     *
     * - 両方が null であれば同等
     * - 他方がクロージャであれば式を比較
     * - 存在する値が等しければ同等
     *
     * @param mixed $obj 等価性を判定されるオブジェクト
     *
     * @return bool オブジェクトと値が等しければ true、それ以外は false
     */
    public function equals($obj)
    {
        if ($this->value === $obj) {
            return true;
        }

        if ($obj instanceof self) {
            return ($this->value === $obj->value);
        }

        return false;
    }

    /**
     * 存在する値がある場合は true を返し、それ以外の場合は false を返します
     *
     * @return bool 存在する値がある場合はtrueを返し、それ以外の場合はfalseを返します
     */
    public function isPresent()
    {
        return !is_null($this->value);
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
     * 値が存在する場合は、その含まれている値を返し、
     * それ以外の場合は、指定されたサプライヤによって作成された例外をスローします
     *
     * @return mixed 保持する非 null 値
     *
     * @throws NoSuchElementException 存在する値がない場合
     *
     * @see isPresent()
     */
    public function get()
    {
        if ($this->isPresent()) {
            return $this->value;
        }

        throw new NoSuchElementException('No value present');
    }

    /**
     * 存在する場合は値を返し、それ以外の場合はotherを返します
     *
     * @param mixed $other 値が存在しない場合は、これの結果が返される
     *
     * @return mixed 存在すれば保持する非 Null 値、それ以外の場合は $other
     *
     * @see isPresent()
     * @see get()
     */
    public function orElse($other)
    {
        return $this->isPresent() ? $this->get() : $other;
    }

    /**
     * 値が存在する場合は、その含まれている値を返し、
     * それ以外の場合は、指定されたサプライヤによって作成された例外をスローします
     *
     * @param string $exception 存在する値がない場合にスローするクラス名
     *
     * @return mixed 保持する非 null 値
     *
     * @throws $exception 存在する値がない場合
     * @throws \RuntimeException $exception 指定した例外クラスが存在しない場合
     *
     * @see isPresent()
     * @see get()
     */
    public function orElseThrow(string $exception)
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
