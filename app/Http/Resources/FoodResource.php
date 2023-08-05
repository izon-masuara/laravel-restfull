<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


// {

//     "id": 1,
//     "name": "Rendang",
//     "price": 23000,
//     "description": "Lorem ipsum dolor amet.",
    // "links": 

    // {
    //     "self": "https://interview-api.pilihjurusan.id/foods/1"
    // }

// }

class FoodResource extends JsonResource
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
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'links' => [
                'self' => "https://interview-api.pilihjurusan.id/foods/" . $this->id
            ]
        ];
    }
}
