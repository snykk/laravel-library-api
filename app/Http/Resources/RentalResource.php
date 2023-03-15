<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RentalResource extends JsonResource
{
    // Used traits declaration
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            "user_id" => $this->user_id,
            "book_id" => $this->book_id,
            "rental_date" => $this->rental_date,
            "rental_duration" => $this->rental_duration,
            "return_date" => $this->return_date ?? "is not returned yet",
            "is_due" => false,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
