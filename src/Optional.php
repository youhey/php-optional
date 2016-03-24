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
        if (is_null($value)) {
            return static::void();
        }

        return new static($value);
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
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * 値がこの Optional と等しいかを示します
     *
     * - 両方が null であれば同等
     * - 他方がクロージャであれば
     * - 存在する値が等しければ同等
     *
     * @param mixed $obj 等価性を判定されるオブジェクト
     *
     * @return bool オブジェクトと値が等しければ true、それ以外は false
     */
    public function equals($obj)
    {
        if (is_null($obj)) {
            return is_null($this->value);
        }

        if ($obj instanceof self) {
            return ($this->value === $obj->value);
        }

        if ($obj instanceof \Closure) {
            $value = ($this->value instanceof \Closure)
                ? $this->value->__invoke()
                : $this->value;
            return ($value === $obj());
        }

        return ($this->value === $obj);
    }

    /**
     * 存在する値がある場合は true を返し、それ以外の場合は false を返します
     *
     * @return bool 存在する値がない場合は true、それ以外の場合は false
     */
    public function isPresent()
    {
        return is_null($this->value);
    }

    /**
     * 値が存在する場合は、その含まれている値を返し、
     * それ以外の場合は、指定されたサプライヤによって作成された例外をスローします
     *
     * @return mixed 保持する非 null 値
     *
     * @throws NoSuchElementException 存在する値がない場合
     */
    public function get()
    {
        if (is_null($this->value)) {
            throw new NoSuchElementException;
        }

        return $this->value;
    }

    /**
     * 存在する場合は値を返し、それ以外の場合はotherを返します
     *
     * @param mixed $other 値が存在しない場合は、これの結果が返される
     *
     * @return mixed 存在すれば保持する非 Null 値、それ以外の場合は $other
     */
    public function orElse($other)
    {
        if (is_null($this->value)) {
            return $other;
        }

        return $this->value;
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
     */
    public function orElseThrow(string $exception)
    {
        if (!class_exists($exception)) {
            throw new \RuntimeException("'{$exception}' exception does not exist.");
        }
        if (is_null($this->value)) {
            $e = new $exception;
            if (!($e instanceof \Throwable)) {
                throw new \RuntimeException("'{$exception}' not implement Throwable.");
            }
            throw $e;
        }

        return $this->value;
    }
}