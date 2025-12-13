<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray($request)
    {
        $translation = $this->translations->first();

        return [
            'id'        => $this->id,
            'picture'   => $this->picture,
            'active'    => $this->active,

            // Translation fields
            'slug'      => optional($translation)->slug,
            'title'     => optional($translation)->title,
            'subtitle'  => optional($translation)->subtitle,
            'description' => optional($translation)->description,
            'content'   => optional($translation)->content,
            'language'  => optional($translation)->language,
            'address'  => optional($translation)->address,

            // Metadata
            'createdAt'     => $this->created_at,
            'medianRating'  => $this->median_rating,
            'totalRating'   => $this->count_rating,
            'ratingMetrics' => $this->rating_metrics,
            
            'meta_title' => optional($translation)->meta_title,
            'meta_description' => optional($translation)->meta_description,
            'meta_keywords' => optional($translation)->meta_keywords,

            'cats' => CatsResource::collection($this->whenLoaded('cats')),

            // Rates list
            'rates' => RateResource::collection(
                $this->whenLoaded('rates')
            ),

             'ordersCount' => $this->orders_count ?? $this->orders()->count(),
            // Owner user
            'user' => $this->whenLoaded('user', fn () => new UserResource($this->user)),
        ];
    }
}
