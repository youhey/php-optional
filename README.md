# php-optional
java.util.Optional like a PHP container object

JAVA8 の Optional がおもしろそうなので、NULL チェックが省略できて便利、程度のノリでPHPに移植してみました。

PHPでジェネリックスみたいなことをすると複雑になりそうなので、値の型は無視したコンテナ・オブジェクトという単純な機能のみです。

mapメソッドとかfilterメソッドは……おもしろそうなので時間があったらまた実装してみたいです。

```php
$value = Optional::ofNullable(findValue())->orElse('not found');

$price = 100;
Optional::ofNullable(jpTax())->ifPresent(function ($tax) use (&$price) {
    $price = $price + $price * $tax;
});

Optional::ofNullable(inputValue())->ifPresent(function ($input) {
    register($input, time());
});
```
