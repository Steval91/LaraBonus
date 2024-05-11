<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employeeQuery = Employee::query();
        $employeeQuery = $this->applySearch($employeeQuery, request('search'));

        return inertia('Employee/EmployeeIndex', [
            'employees' => EmployeeResource::collection(
                $employeeQuery->paginate(10)
            ),
            'search' => request('search') ?? '',
            'can' => [
                'create' => Auth::user()->is_admin == 1,
                'update' => Auth::user()->is_admin == 1,
                'delete' => Auth::user()->is_admin == 1
            ]
        ]);
    }

    protected function applySearch(Builder $query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        });
    }

    public function create()
    {
        return inertia('Employee/EmployeeCreate');
    }

    public function store(EmployeeStoreRequest $request)
    {
        Employee::create($request->validated());

        return redirect()->route('employee.index');
    }

    public function show(string $id)
    {
        $employee = Employee::find($id);

        return inertia('Employee/EmployeeShow', [
            'employee' => EmployeeResource::make($employee),
        ]);
    }

    public function edit(string $id)
    {
        $employee = Employee::find($id);

        return inertia('Employee/EmployeeEdit', [
            'employee' => EmployeeResource::make($employee),
        ]);
    }

    public function update(EmployeeUpdateRequest $request, string $id)
    {
        $employee = Employee::find($id);

        $employee->update($request->validated());

        return redirect()->route('employee.index');
    }

    public function destroy(string $id)
    {
        $employee = Employee::find($id);
        $employee->delete();

        return redirect()->route('employee.index');
    }
}
