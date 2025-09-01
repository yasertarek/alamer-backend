<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NavbarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'link' => $this->link,
            'id' => $this->id,
            'group' => $this->group,
            'order' => $this->order,
            'translations' => [
                ['title' => $this->translations[0]->title,
                'id' => $this->translations[0]->id,
                'navbar_id' => $this->translations[0]->navbar_id,
                'language_id' => $this->translations[0]->language_id,]
            ]
        ];
    }
}
