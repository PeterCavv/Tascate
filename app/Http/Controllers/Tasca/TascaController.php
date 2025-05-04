<?php

namespace App\Http\Controllers\Tasca;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasca\UpdateTascaRequest;
use App\Http\Resources\Tasca\TascaResource;
use App\Models\Tasca;

class TascaController extends Controller
{
    /**
     * Update a Tasca.
     * @return TascaResource
     */
    public function update(UpdateTascaRequest $request, $id)
    {
        $tascaData = $request->validated();
        $tasca = Tasca::findOrFail($id);

        $tasca->update($tascaData);

        return new TascaResource($tasca);

    }

}
