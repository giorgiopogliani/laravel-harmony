<?php

declare(strict_types=1);

namespace Tests\App\Http\Controllers;

use Performing\Harmony\Element;
use Performing\Harmony\Http\ElementController;
use Tests\App\Elements\PostElement;

class PostController extends ElementController
{
    protected function element(): Element
    {
        return new PostElement();
    }
}
