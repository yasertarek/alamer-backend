<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    public function toArray($request)
    {
        // Fetch first translation
        $translation = $this->translations->first();

        return [
            'id'          => $this->id,
            'picture'     => $this->picture,
            'createdAt'   => $this->created_at,
            'updatedAt'   => $this->updated_at,
            'published_at'=> $this->published_at,
            'active'      => $this->active,

            // Categories
            'cats' => CatsResource::collection($this->whenLoaded('cats')),

            // Blog owner
            'user_id' => $this->whenLoaded('blog', fn () => $this->blog->user_id),

            // Translation fields
            'slug'     => optional($translation)->slug,
            'title'    => optional($translation)->title,
            'subtitle' => optional($translation)->subtitle,
            'content'  => optional($translation)->content,
            'language' => optional($translation)->language,

            // User info
            'user' => $this->whenLoaded('user', fn () => new UserResource($this->user)),

            // Comments (paginated or count)
            $this->mergeWhen($this->relationLoaded('comments'), function () {

                // You ALREADY loaded comments via ->with('comments') so DO NOT query again.
                $comments = $this->comments()
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);

                return [
                    'comments' => [
                        'meta' => [
                            'current_page' => $comments->currentPage(),
                            'last_page'    => $comments->lastPage(),
                            'per_page'     => $comments->perPage(),
                            'total'        => $comments->total(),
                        ],
                        'links' => [
                            'first' => $comments->url(1),
                            'last'  => $comments->url($comments->lastPage()),
                            'prev'  => $comments->previousPageUrl(),
                            'next'  => $comments->nextPageUrl(),
                        ],
                        'data' => CommentResource::collection($comments->items()),
                    ]
                ];
            }),

            $this->mergeWhen(! $this->relationLoaded('comments'), [
                'commentsCount' => $this->comments_count,
            ]),

            // Reactions (collection or count)
            'reactions' => $this->whenLoaded('reactions', fn () => ReactionResource::collection($this->reactions)),

            $this->mergeWhen(! $this->relationLoaded('reactions'), [
                'reactionsCount' => $this->reactions_count,
            ]),
        ];
    }
}
