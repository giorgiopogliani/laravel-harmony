<?php

declare(strict_types=1);

namespace Performing\Harmony\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return parent::toArray($request);
    }
}
