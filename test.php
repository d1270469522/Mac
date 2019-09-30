<?php

/**
 * @description        : 天尽头流浪
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-07-25 16:59:01
 * @Last Modified by   : 天尽头流浪
 */

// static $a = 1;
// function num1(){
//     static $a = 0;
//     echo  $a++;
// }

// function num2(){
//     static $a = 10;
//     global $a;
//     echo $a++;
// }

// function num3(){
//     static $a = 100;
//     return $a++;
// }

// echo num1();
// echo num1();
// echo num1();
// echo num1();
// echo num1();
// echo num1();

// num2();
// echo $a;

// echo num3();
// echo num3();
// echo num3();




//  class first
//  {
//     public $num = '123';

//     //声明一个静态属性
//     static $name = '456';
//     //声明一个静态方法
//     public static function self_use(){
//         echo static::method();
//         // echo self::out();
//     }
//     public static function static_use(){
//         // echo static::out();
//     }
//     public static function method(){
//         echo 'first';
//     }

//     //这是一个错误的调用
//     public function my_wrong(){
//         //echo self::dis();
//     }
//     public function dis(){
//         echo '只是做一个展示';
//     }


// }
// class second extends first
// {
//     public static function method(){
//         echo 'second';
//     }

//     public static function method2(){
//         echo self::method();
//     }
// }


// echo first::$name;
// $one = new first();
// echo $one::$name;
// echo $one->num;
// $one->self_use();
// $one->method();

// $two = new second();
// $two->self_use();
// $two->method2();

79,80,81,82,83,84,85,86,87,88





class staticTest {
    public function test() {
        $i = 0;
        $i++;
    }


    public static function testStatic() {
        $i = 0;
        $i++;
    }
}
$start = microtime(true);
for($i=0;$i<100000000;$i++) {
    $test = new staticTest();
    $test->test();
}
echo (microtime(true) - $start) ."\n";
$start = microtime(true);
    for($i=0;$i<100000000;$i++) {
    staticTest::testStatic();
}
echo microtime(true) - $start;
?>
