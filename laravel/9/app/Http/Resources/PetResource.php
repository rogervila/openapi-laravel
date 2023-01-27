<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $payload = [
            'id' => $this->id,
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],
            'name' => $this->name,
            'photoUrls' => [],
            'tags' => [],
            'status' => $this->status,
        ];

        foreach ($this->tags as $tag) {
            $payload['tags'][] = ['id' => $tag->id, 'name' => $tag->name];
        }

        foreach ($this->photos as $photo) {
            $payload['photoUrls'][] = $photo->url;
        }

        return $payload;
    }
}
