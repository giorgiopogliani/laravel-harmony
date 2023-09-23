<?php

namespace Performing\Harmony\Http;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Performing\Harmony\Element;
use Performing\Harmony\Http\Contracts;
use Performing\Harmony\Http\Concerns;

abstract class ElementController extends Controller implements
    Contracts\ElementIndexable,
    Contracts\ElementCreatable,
    Contracts\ElementEditable,
    Contracts\ElementShowable
{
    use AuthorizesRequests;
    use ValidatesRequests;
    use Concerns\ElementIndex;
    use Concerns\ElementCreate;
    use Concerns\ElementEdit;
    use Concerns\ElementShow;

    abstract protected function element(): Element;
}
