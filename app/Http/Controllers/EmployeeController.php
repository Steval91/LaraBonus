<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public function index()
    {
        $employeeQuery = Employee::query();

        $employeeQuery = $this->applySearch($employeeQuery, request('search'));

        // return inertia('Employee/Index', [
        //     'employees' => EmployeeResource::collection(
        //         $employeeQuery->paginate(5)
        //     ),
        //     'search' => request('search') ?? ''
        // ]);

        $employees = Employee::all(); // Misalnya, Anda mendapatkan data pegawai dari database

        return Inertia::render('Bonus/Index', [
            'employees' => $employees // Melewatkan data pegawai langsung ke komponen Vue
        ]);
    }

    protected function applySearch(Builder $query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public function create()
    {
        $classes = EmployeeResource::collection(Employee::all());

        return inertia('Employee/Create', [
            'classes' => $classes
        ]);
    }

    public function store(EmployeeStoreRequest $request)
    {
        Employee::create($request->validated());

        return redirect()->route('employee.index');
    }

    public function edit(Employee $employee)
    {
        $classes = EmployeeResource::collection(Employee::all());

        return inertia('Employee/Edit', [
            'employee' => EmployeeResource::make($employee),
            'classes' => $classes
        ]);
    }

    public function update(EmployeeUpdateRequest $request, Employee $employee)
    {
        $employee->update($request->validated());

        return redirect()->route('employee.index');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employee.index');
    }
}
