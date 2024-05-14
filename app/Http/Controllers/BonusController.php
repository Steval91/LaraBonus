<?php

namespace App\Http\Controllers;

use App\Http\Requests\BonusStoreRequest;
use App\Http\Requests\BonusUpdateRequest;
use App\Http\Resources\BonusDetailResource;
use App\Http\Resources\BonusResource;
use App\Http\Resources\EmployeeResource;
use App\Models\Bonus;
use App\Models\BonusDetail;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BonusController extends Controller
{
    public function index()
    {
        $bonusQuery = Bonus::query();
        $bonusQuery = $this->applySearch($bonusQuery, request('search'));

        return inertia('Bonus/BonusIndex', [
            'bonuses' => BonusResource::collection(
                $bonusQuery->paginate(10)
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
            $query->where('total_bonus', 'like', '%' . $search . '%');
        });
    }

    public function create()
    {
        $employees = EmployeeResource::collection(Employee::all());

        return inertia('Bonus/BonusCreate', [
            'employees' => $employees
        ]);
    }

    public function store(BonusStoreRequest $request)
    {
        $bonus = Bonus::create($request->validated());

        foreach ($request->bonus_detail as $line) {
            BonusDetail::create([
                'bonus_id' => $bonus->id,
                'employee_id' => $line['employee_id'],
                'bonus_percentage' => $line['percentage'],
                'bonus' => ($bonus->total_bonus * $line['percentage'] / 100)
            ]);
        }

        return redirect()->route('bonus.index');
    }

    public function show(string $id)
    {
        $bonus = Bonus::find($id);
        $bonus_detail = BonusDetail::where('bonus_id', $id)->get();

        return inertia('Bonus/BonusShow', [
            'bonus' => BonusResource::make($bonus),
            'bonus_detail' => BonusDetailResource::collection($bonus_detail),
        ]);
    }

    public function edit(string $id)
    {
        $response = Gate::inspect('update', Bonus::class);
        $message = '';
        if ($response->denied()) {
            $message = $response->message();
            return inertia('Error/Unauthorized', ['message' => $message]);
        }

        $bonus = Bonus::find($id);
        $bonus_detail = BonusDetail::where('bonus_id', $id)->get();
        $employees = EmployeeResource::collection(Employee::all());

        return inertia('Bonus/BonusEdit', [
            'bonus' => BonusResource::make($bonus),
            'bonus_detail' => BonusDetailResource::collection($bonus_detail),
            'employees' => $employees
        ]);
    }

    public function update(BonusUpdateRequest $request, string $id)
    {
        $response = Gate::inspect('update', Bonus::class);
        $message = '';
        if ($response->denied()) {
            $message = $response->message();
            return inertia('Error/Unauthorized', ['message' => $message]);
        }

        $bonus = Bonus::find($id);
        $bonus->update($request->validated());

        $bonus_detail = BonusDetail::where('bonus_id', $id)->get();
        foreach ($bonus_detail as $line) {
            $line->delete();
        }

        foreach ($request->bonus_detail as $line) {
            BonusDetail::create([
                'bonus_id' => $bonus->id,
                'employee_id' => $line['employee_id'],
                'bonus_percentage' => $line['percentage'],
                'bonus' => ($bonus->total_bonus * $line['percentage'] / 100)
            ]);
        }

        return redirect()->route('bonus.index');
    }

    public function destroy(string $id)
    {
        $response = Gate::inspect('delete', Bonus::class);
        $message = '';
        if ($response->denied()) {
            $message = $response->message();
            return inertia('Error/Unauthorized', ['message' => $message]);
        }

        $bonus_detail = BonusDetail::where('bonus_id', $id)->get();
        foreach ($bonus_detail as $line) {
            $line->delete();
        }

        $bonus = Bonus::find($id);
        $bonus->delete();

        return redirect()->route('bonus.index');
    }
}
