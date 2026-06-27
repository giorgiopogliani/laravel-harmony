<?php

declare(strict_types=1);

use Illuminate\Contracts\Support\Arrayable;
use Tests\TestCase;

/*
 |--------------------------------------------------------------------------
 | Test Case
 |--------------------------------------------------------------------------
 |
 | The closure you provide to your test functions is always bound to a specific PHPUnit test
 | case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
 | need to change it using the "uses()" function to bind a different classes or traits.
 |
 */

uses(TestCase::class)->in('Arch');

/*
 |--------------------------------------------------------------------------
 | Functions
 |--------------------------------------------------------------------------
 |
 | While Pest is very powerful out-of-the-box, you may have some testing code specific to your
 | project that you don't want to repeat in every file. Here you can also expose helpers as
 | global functions to help you to reduce the number of lines of code in your test files.
 |
 */

function to_array(mixed $array)
{
    if (is_array($array)) {
        return array_map('to_array', $array);
    }

    if ($array instanceof Arrayable) {
        return $array->toArray();
    }

    return $array;
}
