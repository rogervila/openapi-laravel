<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\OpenAPI\PetRequest;
use App\Models\Pet;
use Illuminate\Http\Response;
use LaravelOpenAPI\OpenAPI;

class DeletePetController extends Controller
{
    public function __invoke(PetRequest $request, int $petId): Response
    {
        if (is_null($pet = Pet::find($petId))) {
            OpenAPI::abort(Response::HTTP_NOT_FOUND)->forRequest($request);
        }

        $pet->delete();

        return OpenAPI::response()->noContent()->forRequest($request);
    }
}
