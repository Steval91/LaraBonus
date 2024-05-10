<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $userQuery = User::query();
        $userQuery = $this->applySearch($userQuery, request('search'));

        return inertia('User/UserIndex', [
            'users' => UserResource::collection(
                $userQuery->paginate(10)
            ),
            'search' => request('search') ?? '',
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
        return inertia('User/UserCreate');
    }

    public function store(UserStoreRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['is_admin'] = $request->is_admin ? 1 : 0;
        $validatedData['password'] = Hash::make('password');

        User::create($validatedData);

        return redirect()->route('user.index');
    }

    public function show(string $id)
    {
        $user = User::find($id);

        return inertia('User/UserShow', [
            'user' => UserResource::make($user),
        ]);
    }

    public function edit(string $id)
    {
        $user = User::find($id);

        return inertia('User/UserEdit', [
            'user' => UserResource::make($user),
        ]);
    }

    public function update(UserUpdateRequest $request, string $id)
    {
        $user = User::find($id);

        $validatedData = $request->validated();
        $validatedData['is_admin'] = $request->is_admin ? 1 : 0;

        $user->update($validatedData);

        return redirect()->route('user.index');
    }

    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('user.index');
    }
}
