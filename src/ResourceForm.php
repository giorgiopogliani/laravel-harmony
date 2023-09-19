<?php

namespace Performing\Harmony;

use Performing\Harmony\Components\Form\Input;

interface ResourceForm
{
    /** @return Input[] */
    public function fields(): array;

    /** @return Input[] */
    public function data(): array;
}
