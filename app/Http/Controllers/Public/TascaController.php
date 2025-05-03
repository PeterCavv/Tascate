<?php

namespace app\http\controllers\public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tasca\TascaCollection;
use App\Http\Resources\Tasca\TascaResource;
use App\Models\Tasca;

class tascacontroller extends Controller
{
    /**
     * Get all the operational tascas.
     * @return TascaCollection
     */
    public function index()
    {
        return new TascaCollection(Tasca::paginate(5));

    }

    /**
     * Get a Tasca searched by its ID.
     * @param $id Tasca's ID
     * @return TascaResource
     */
    public function show($id)
    {
        return new TascaResource(Tasca::findorfail($id));

    }
}
