<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Inertia;

Route::middleware('web')->group(function () {
    Route::group(['middleware' => ['auth']], function () {
        Route::get('dashboard', fn () => Inertia::render('harmony::dashboard'))->name('dashboard');

        Route::macro('harmoned', function ($uri, $resource) {
            Route::get("$uri", [$resource, 'index'])->name("$uri.index");
            Route::get("$uri/create", [$resource, 'create'])->name("$uri.create");
            Route::post("$uri", [$resource, 'store'])->name("$uri.store");
            Route::get("$uri/{model}", [$resource, 'show'])->name("$uri.show");
            Route::get("$uri/{model}/edit", [$resource, 'edit'])->name("$uri.edit");
            Route::put("$uri/{model}", [$resource, 'update'])->name("$uri.update");
            Route::delete("$uri/{model}", [$resource, 'destroy'])->name("$uri.destroy");
            Route::put("$uri/batch/{action}", [$resource, 'batchAction'])->name("$uri.batchAction");
        });
    });

    require __DIR__ . '/auth.php';
});
