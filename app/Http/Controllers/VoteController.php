<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteStoreRequest;
use App\Http\Requests\VoteUpdateRequest;
use App\Http\Resources\VoteDetailResource;
use App\Http\Resources\VoteResource;
use App\Http\Resources\EmployeeResource;
use App\Models\Vote;
use App\Models\VoteDetail;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class VoteController extends Controller
{
    public function index()
    {
        $voteQuery = Vote::query();
        $voteQuery = $this->applySearch($voteQuery, request('search'));

        return inertia('Vote/VoteIndex', [
            'votees' => VoteResource::collection(
                $voteQuery->paginate(10)
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
            $query->where('total_vote', 'like', '%' . $search . '%');
        });
    }

    public function create()
    {
        $employees = EmployeeResource::collection(Employee::all());

        return inertia('Vote/VoteCreate', [
            'employees' => $employees
        ]);
    }

    public function store(VoteStoreRequest $request)
    {
        $vote = Vote::create($request->validated());

        foreach ($request->vote_detail as $line) {
            VoteDetail::create([
                'vote_id' => $vote->id,
                'employee_id' => $line['employee_id'],
                'score' => $line['score'],
            ]);
        }

        return redirect()->route('vote.index');
    }

    public function show(string $id)
    {
        $vote = Vote::find($id);
        $vote_detail = VoteDetail::where('vote_id', $id)->get();

        return inertia('Vote/VoteShow', [
            'vote' => VoteResource::make($vote),
            'vote_detail' => VoteDetailResource::collection($vote_detail),
        ]);
    }

    public function edit(string $id)
    {
        $response = Gate::inspect('update', Vote::class);
        $message = '';
        if ($response->denied()) {
            $message = $response->message();
            return inertia('Error/Unauthorized', ['message' => $message]);
        }

        $vote = Vote::find($id);
        $vote_detail = VoteDetail::where('vote_id', $id)->get();
        $employees = EmployeeResource::collection(Employee::all());

        return inertia('Vote/VoteEdit', [
            'vote' => VoteDetailResource::make($vote),
            'vote_detail' => VoteDetailResource::collection($vote_detail),
            'employees' => $employees
        ]);
    }

    public function update(VoteUpdateRequest $request, string $id)
    {
        $response = Gate::inspect('update', Vote::class);
        $message = '';
        if ($response->denied()) {
            $message = $response->message();
            return inertia('Error/Unauthorized', ['message' => $message]);
        }

        $vote = Vote::find($id);
        $vote->update($request->validated());

        $vote_detail = VoteDetail::where('vote_id', $id)->get();
        foreach ($vote_detail as $line) {
            $line->delete();
        }

        foreach ($request->vote_detail as $line) {
            VoteDetail::create([
                'vote_id' => $vote->id,
                'employee_id' => $line['employee_id'],
                'score' => $line['score'],
            ]);
        }

        return redirect()->route('vote.index');
    }

    public function destroy(string $id)
    {
        $response = Gate::inspect('delete', Vote::class);
        $message = '';
        if ($response->denied()) {
            $message = $response->message();
            return inertia('Error/Unauthorized', ['message' => $message]);
        }

        $vote_detail = VoteDetail::where('vote_id', $id)->get();
        foreach ($vote_detail as $line) {
            $line->delete();
        }

        $vote = Vote::find($id);
        $vote->delete();

        return redirect()->route('vote.index');
    }
}
