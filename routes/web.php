<?php

declare(strict_types=1);

use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::macro('harmoned', function ($uri, $resource) {
            Route::get("$uri", [$resource, 'index'])->name("$uri.index");
            Route::get("$uri/create", [$resource, 'create'])->name("$uri.create");
            Route::post("$uri", [$resource, 'store'])->name("$uri.store")
                ->middleware([HandlePrecognitiveRequests::class]);
            Route::get("$uri/{model}", [$resource, 'show'])->name("$uri.show");
            Route::get("$uri/{model}/edit", [$resource, 'edit'])->name("$uri.edit");
            Route::put("$uri/{model}", [$resource, 'update'])->name("$uri.update")
                ->middleware([HandlePrecognitiveRequests::class]);
            Route::delete("$uri/{model}", [$resource, 'destroy'])->name("$uri.destroy");
            Route::put("$uri/batch/{action}", [$resource, 'batchAction'])->name("$uri.batchAction");
        });
    });
});
