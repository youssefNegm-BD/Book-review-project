<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            "book_id"=> $this->id,
            'name'=>$this->title,
            'author'=>$this->author,
            'description'=>$this->description,
            'created_at'=>$this->created_at,

            'reviews'=>$this->reviews->map(function ($review) {
                return [
                    // "review"=>$review->review,
                    "user_id"=>$review->user->id,
                    "rating"=> $review->rating,
                    "status"=> $review->status
                ];
            }),
        ];
    }
}
