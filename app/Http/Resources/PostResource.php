<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'excerpt' => str($this->body)->limit(80),

            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->diffForHumans(), // 2 days ago
        ];
    }
}
