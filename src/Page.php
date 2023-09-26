<?php

namespace Performing\Harmony;

use Inertia\Inertia;
use Illuminate\Support\Traits\Macroable;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Components\Tables\Table;
use Performing\Harmony\Components\Forms\Form;

class Page extends Component
{
    use Macroable;

    protected $data = [];

    public static function make(string $title)
    {
        return new static($title);
    }

    public function __construct(string $title)
    {
        $this->data['title'] = $title;
    }

    public function breadcrumbs(...$breadcrumbs)
    {
        $this->data['breadcrumbs'] = $breadcrumbs;

        return $this;
    }

    public function actions(...$actions)
    {
        $this->data['actions'] = $actions;

        return $this;
    }

    public function table(Table $table)
    {
        $this->data['table'] = $table;

        return $this;
    }

    public function form(Form $form)
    {
        $this->data['form'] = $form;

        return $this;
    }

    public function __call($name, $arguments)
    {
        $this->data[$name] = $arguments[0];

        return $this;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function render($component)
    {
        return Inertia::render($component, $this->toArray());
    }
}
