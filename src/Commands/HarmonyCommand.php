<?php

declare(strict_types=1);

namespace Performing\Harmony\Commands;

use Illuminate\Console\Command;

class HarmonyCommand extends Command
{
    public $signature = 'harmony';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
