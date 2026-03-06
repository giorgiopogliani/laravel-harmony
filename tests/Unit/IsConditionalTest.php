<?php

declare(strict_types=1);

use Performing\Harmony\Concerns\IsConditional;

it('is visible by default', function () {
    $subject = new class {
        use IsConditional;
    };

    expect($subject->isVisible())->toBeTrue();
});

it('can be hidden with when false', function () {
    $subject = new class {
        use IsConditional;
    };

    $subject->when(false);

    expect($subject->isVisible())->toBeFalse();
});

it('returns static for fluent chaining', function () {
    $subject = new class {
        use IsConditional;
    };

    $result = $subject->when(true);

    expect($result)->toBe($subject);
});
