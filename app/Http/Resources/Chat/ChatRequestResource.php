<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "model" => $this['model'],
            "messages" => [
                [
                    "role" => $this['messages']['role'],
                    "content" => $this['messages']['content']
                ]
            ],
        ];
    }
}
