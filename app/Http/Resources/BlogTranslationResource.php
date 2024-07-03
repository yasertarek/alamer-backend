<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogTranslationResource extends JsonResource
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
            'blog_id' => $this->blog_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'content' => $this->content,
            'createdAt' => $this->created_at,
        ];
    }
}
