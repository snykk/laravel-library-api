<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{

    // protected $removeAuthors = false;
    // protected $removePublishers = false;


    // public function __construct($remAuthors = false,  $remPublisers = false)
    // {
    //     $this->removeAuthors = $remAuthors;
    //     $this->removePublishers = $remPublisers;
    // }

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
        // return parent::toArray($request);

        // $data = [
        //     "id" => $this->id,
        //     "title" => $this->title,
        //     "description" => $this->description,
        //     "rating" => $this->rating,
        //     // // "author_id" => $this->author_id,
        //     // "authors" => $this->author,
        //     // // "publisher_id" => $this->publisher_id,
        //     // "publishers" => $this->publisher,
        //     "created_at" => $this->created_at,
        //     "updated_at" => $this->updated_at,
        // ];

        // if (!$this->removeAuthors) {
        //     $data["authors"] = $this->author;
        // }
        // if (!$this->removePublishers) {
        //     $data["publishers"] = $this->publisher;
        // }

        // return $data;

        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "rating" => $this->rating,
            // "author_id" => $this->author_id,
            "authors" => $this->author,
            // "publisher_id" => $this->publisher_id,
            "publishers" => $this->publisher,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
