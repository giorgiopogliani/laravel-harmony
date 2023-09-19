<?php

namespace Performing\Harmony;

use Illuminate\Contracts\Support\Arrayable;

class Flash implements Arrayable
{
    protected array $messages = [];

    public function __construct()
    {
        $this->messages = session()->get('harmony.flash', []);
    }

    public function __destruct()
    {
        session()->flash('harmony.flash', $this->messages);
    }

    public function success(string $message)
    {
        $this->messages[] = [
            'type' => 'success',
            'message' => $message,
        ];
    }

    public function error(string $message)
    {
        $this->messages[] = [
            'type' => 'error',
            'message' => $message,
        ];
    }

    public function info(string $message)
    {
        $this->messages[] = [
            'type' => 'info',
            'message' => $message,
        ];
    }

    public function toArray(): array
    {
        return [
            'messages' => $this->messages
        ];
    }
}
