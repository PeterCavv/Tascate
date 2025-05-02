<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Reservation\ReservationCollection;
use App\Http\Resources\Reservation\ReservationResource;
use App\Models\Customer;
use App\Models\Reservation;
use function Pest\Laravel\get;

class ReservationController extends Controller
{
    public function index($id)
    {
        $customer = Customer::findOrFail($id);

        return new ReservationCollection(
            $customer->reservations()->paginate(10)
        );
    }
}
