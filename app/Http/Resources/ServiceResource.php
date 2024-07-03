<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray($request)
    {
        $translation = $this->translations->first();
        $data = [
            'id' => $this->id,
            'picture' => $this->picture,
            'slug' => $translation ? $translation->slug : null,
            'title' => $translation ? $translation->title : null,
            'subtitle' => $translation ? $translation->subtitle : null,
            'content' => $translation ? $translation->content : null,
        ];
        return $data;

        // $data = [
        //     'id' => $this->id,
        //     'title' => $this->translations->first() ? $this->translations->first()->title : null,
        //     'subtitle' => $this->translations->first() ? $this->translations->first()->subtitle : null,
        //     'content' => $this->translations->first() ? $this->translations->first()->content : null,
        //     'created_at' => $this->created_at,
        //     'updated_at' => $this->updated_at,
        //     'user' => new UserResource($this->whenLoaded('user')),
        // ];

        // if ($this->resource->relationLoaded('comments')) {
        //     $data['comments'] = CommentResource::collection($this->comments);
        // } else {
        //     $data['comments_count'] = $this->comments_count;
        // }

        // if ($this->resource->relationLoaded('reaction')) {
        //     $data['reaction'] = ReactionResource::collection($this->reaction);
        // } else {
        //     $data['reaction_count'] = $this->reaction_count;
        // }

        // return $data;

    }
}
