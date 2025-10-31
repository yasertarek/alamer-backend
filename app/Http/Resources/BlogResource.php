<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Comment;

class BlogResource extends JsonResource
{
    public function toArray($request)
    {
        $translation = $this->translations->first();
        $data = [
            'id' => $this->id,
            'picture' => $this->picture,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'cats' => CatsResource::collection($this->whenLoaded('cats')),
            'user_id' => $this->whenLoaded('blog', function () {
                return $this->blog->user_id;
            }),
            'published_at' => $this->published_at,
            'active' => $this->active,
            'slug' => $translation ? $translation->slug : null,
            'title' => $translation ? $translation->title : null,
            'subtitle' => $translation ? $translation->subtitle : null,
            'content' => $translation ? $translation->content : null,
            'language' => $translation ? $translation->language : null,
            'user' => $this->whenLoaded('user', function () {
                return new UserResource($this->user);
            }),
        ];

            // dd($translations);
            // $data['subtitle'] = BlogTranslationResource::collection($this->translations);
            // $data['content'] = BlogTranslationResource::collection($this->translations);
        
        if ($this->resource->relationLoaded('comments')) {
        $comments = Comment::where('blog_id', $this->id)->with('user')->orderBy('created_at', 'desc')->paginate(5);
            $commentsCollection = CommentResource::collection($comments->items());
            $data['comments'] = [
                'meta' => [
                    'current_page' => $comments->currentPage(),
                    'last_page' => $comments->lastPage(),
                    'per_page' => $comments->perPage(),
                    'total' => $comments->total(),
                ],
                'links' => [
                    'first' => $comments->url(1),
                    'last' => $comments->url($comments->lastPage()),
                    'prev' => $comments->previousPageUrl(),
                    'next' => $comments->nextPageUrl(),
                ],
                'data' => $commentsCollection
            ];
        } else {
            $data['commentsCount'] = $this->comments_count;
        }

        if ($this->resource->relationLoaded('reactions')) {
            $data['reactions'] = ReactionResource::collection($this->reactions);
        } else {
            $data['reactionsCount'] = $this->reactions_count;
        }

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
