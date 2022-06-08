<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Meeting;
use Illuminate\Support\Facades\Auth;

class HomeController
{
    public function index()
    {
        
        abort_if(Gate::denies('dashboard_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $totalTickets = Meeting::count();
        $openTickets = Meeting::whereHas('status', function($query) {
            $query->whereName('Open');
        })->count();
        $closedTickets = Meeting::whereHas('status', function($query) {
            $query->whereName('Closed');
        })->count();

        return view('home', compact('totalTickets', 'openTickets', 'closedTickets'));
    }
}
