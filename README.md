# php-optional
java.util.Optional like a PHP container object

PHP でその必要性があるか……、と言うと相当謎ですが、Swift の Optional型とか便利だなと思って、JAVA8 の Optional を参考にして（NULL チェックを省略できて便利、程度のノリで）PHP 版の Optional 型っぽいコンテナ・オブジェクトです。

PHPでジェネリックスみたいなことをすると実装が複雑な割に誰も得をしなそうなので、値の型は無視して単純な機能のみ実装しています。

mapメソッドとかfilterメソッドは……おもしろそうなので時間があったらまた実装してみたいです。

```php
$value = Optional::ofNullable(findValue())->orElse('not found');

$price = 100;
Optional::ofNullable(jpTax())->ifPresent(function ($tax) use (&$price) {
    $price = $price + $price * $tax;
});

Optional::ofNullable(inputValue())->ifPresent(function () {
    register($this->value, time());
});
```
