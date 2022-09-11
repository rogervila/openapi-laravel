<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\OpenAPI\PetRequest;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use Illuminate\Http\JsonResponse;
use LaravelOpenAPI\OpenAPI;

class FindPetByIdController extends Controller
{
    public function __invoke(PetRequest $request, int $petId): JsonResponse
    {
        if (is_null($pet = Pet::find($petId))) {
            OpenAPI::abort(JsonResponse::HTTP_NOT_FOUND)->forRequest($request);
        }

        return OpenAPI::response(new PetResource($pet))->forRequest($request);
    }
}
