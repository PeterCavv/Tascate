<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fav\StoreFavRequest;
use App\Models\User;

class FavController extends Controller
{
    /**
     * Add or remove a Tasca from favorites.
     * @param StoreFavRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addOrRemoveFav(StoreFavRequest $request)
    {
        $favData = $request->validated();

        $user = User::where('id', 1)->first();
        $tascaId = $favData['tascas_id'];

        $attached = $user->tascasFav()->toggle($tascaId);

        if(!empty($attached['attached'])) {
            return response()->json(['message' => 'Tasca added to favorites.'], 200);
        } else {
            return response()->json(['message' => 'Tasca removed from favorites.'], 200);
        }
    }

}
