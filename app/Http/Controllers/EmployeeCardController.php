<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

class EmployeeCardController extends Controller
{
    public function show(string $slug): View
    {
        $employee = Employee::query()
            ->where('slug', $slug)
            ->where('tampilkan_kartu', true)
            ->firstOrFail();

        return ViewFacade::make('card.show', ['employee' => $employee]);
    }
}
