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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                'updateBonus' => Auth::user()->is_admin == 1,
                'deleteBonus' => Auth::user()->is_admin == 1
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

        return inertia('bonus/BonusShow', [
            'bonus' => BonusResource::make($bonus),
        ]);
    }

    public function edit(string $id)
    {
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
        $bonus_detail = BonusDetail::where('bonus_id', $id)->get();
        foreach ($bonus_detail as $line) {
            $line->delete();
        }

        $bonus = Bonus::find($id);
        $bonus->delete();

        return redirect()->route('bonus.index');
    }
}
