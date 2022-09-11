<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\OpenAPI\PetRequest;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use LaravelOpenAPI\OpenAPI;

class FindPetsByTagsController extends Controller
{
    public function __invoke(PetRequest $request): JsonResponse
    {
        $pets = Pet::whereHas('tags', function (Builder $query) use ($request) {
            $query->whereIn('name', $request->tags);
        })->get();

        return OpenAPI::response(PetResource::collection($pets))
            ->forRequest($request);
    }
}
