<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-8
 * Time: 下午3:01
 */

require_once '../vendor/autoload.php';

use Yeelight\Specification\Collection\Collection;

if (! function_exists('collect')) {
    /**
     * Create a collection from the given value.
     *
     * @param  mixed  $value
     * @return \Yeelight\Specification\Collection\Collection
     */
    function collect($value = null)
    {
        return new Collection($value);
    }
}

// 创建集合
$collection = collect([1, 2, 3]);

var_dump($collection); // [1, 2, 3]

// 返回该集合所代表的底层 数组
var_dump(collect([1, 2, 3])->all()); // [1, 2, 3]

// 返回集合中所有项目的平均值
var_dump(collect([1, 2, 3, 4, 5])->avg()); // 3

// 如果集合包含了嵌套数组或对象，你可以通过传递「键」来指定使用哪些值计算平均值
$collection = collect([
    ['name' => 'JavaScript: The Good Parts', 'pages' => 176],
    ['name' => 'JavaScript: The Definitive Guide', 'pages' => 1096],
]);

var_dump($collection->avg('pages')); // 636

// 将集合拆成多个指定大小的较小集合
$collection = collect([1, 2, 3, 4, 5, 6, 7]);

$chunks = $collection->chunk(4);

var_dump($chunks->toArray());

// [[1, 2, 3, 4], [5, 6, 7]]

// 将多个数组组成的集合合成单个一维数组集合
$collection = collect([[1, 2, 3], [4, 5, 6], [7, 8, 9]]);

$collapsed = $collection->collapse();

var_dump($collapsed->all());

// [1, 2, 3, 4, 5, 6, 7, 8, 9]

// 将集合的值作为「键」，合并另一个数组或者集合作为「键」对应的值
$collection = collect(['name', 'age']);

$combined = $collection->combine(['George', 29]);

var_dump($combined->all());

// ['name' => 'George', 'age' => 29]

// 判断集合是否含有指定项目

$collection = collect(['name' => 'Desk', 'price' => 100]);

var_dump($collection->contains('Desk'));

// true

var_dump($collection->contains('New York'));

// false

$collection = collect([
    ['product' => 'Desk', 'price' => 200],
    ['product' => 'Chair', 'price' => 100],
]);

var_dump($collection->contains('product', 'Bookcase'));

// false

$collection = collect([1, 2, 3, 4, 5]);

$collection->contains(function ($value, $key) {
    return $value > 5;
});

// false

// 返回该集合内的项目总数
$collection = collect([1, 2, 3, 4]);

$collection->count();

// 4