<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\OpenAPI\PetRequest;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use Illuminate\Http\JsonResponse;
use LaravelOpenAPI\OpenAPI;

class FindPetsByStatusController extends Controller
{
    public function __invoke(PetRequest $request): JsonResponse
    {
        return OpenAPI::response(PetResource::collection(Pet::where('status', $request->status)->get()))
            ->forRequest($request);
    }
}
