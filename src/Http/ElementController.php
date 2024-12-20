<?php

declare(strict_types=1);

namespace Performing\Harmony\Http;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Performing\Harmony\Element;

abstract class ElementController extends Controller implements
    Contracts\ElementIndexable,
    Contracts\ElementCreatable,
    Contracts\ElementEditable,
    Contracts\ElementShowable,
    Contracts\ElementDeletable,
    Contracts\ElementStorable,
    Contracts\ElementUpdatable
{
    use AuthorizesRequests;
    use ValidatesRequests;
    use Concerns\ElementIndex;
    use Concerns\ElementCreate;
    use Concerns\ElementEdit;
    use Concerns\ElementShow;
    use Concerns\ElementStore;
    use Concerns\ElementUpdate;
    use Concerns\ElementDelete;

    public function __construct()
    {
        app()->singleton('element', fn () => $this->element());
    }

    abstract protected function element(): Element;
}
