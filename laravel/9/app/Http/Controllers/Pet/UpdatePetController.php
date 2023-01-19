<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\OpenAPI\PetRequest;
use App\Http\Resources\PetResource;
use App\Models\Category;
use App\Models\Pet;
use App\Models\Photo;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use LaravelOpenAPI\OpenAPI;

class UpdatePetController extends Controller
{
    public function __invoke(PetRequest $request): JsonResponse
    {
        if (is_null($pet = Pet::find($request->id))) {
            OpenAPI::abort(JsonResponse::HTTP_NOT_FOUND)->forRequest($request);
        }

        try {
            DB::beginTransaction();

            $pet->name = $request->name;
            $pet->status = $request->status;
            $pet->category_id = Category::firstOrCreate([
                'name' => strtolower($request->category['name']),
            ])->id;

            foreach ($request->tags as $tag) {
                $pet->tags()->sync(
                    Tag::firstOrCreate([
                        'name' => strtolower($tag['name']),
                    ])->id,
                );
            }

            foreach ($request->photoUrls as $url) {
                Photo::firstOrCreate([
                    'url' => $url,
                    'pet_id' => $pet->id,
                ]);
            }

            $pet->save();

            DB::commit();

            return OpenAPI::response(new PetResource($pet))->forRequest($request);
        } catch (\Illuminate\Database\QueryException) {
            DB::rollBack();
            OpenAPI::abort(JsonResponse::HTTP_METHOD_NOT_ALLOWED)->forRequest($request);
        }
    }
}
