<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $users = User::all();

        if (auth()->check()) {
            $authUserId = auth()->user()->id;
        } else {
            $authUserId = null;
        }
        return Inertia::render('Users/Users', [
            'users' => $users,
            'authUserId' => $authUserId,
        ]);
    }

    public function show(User $user)
    {
        if ($user->avatar) {
            $user->avatar = asset('storage/' . $user->avatar);
        }

        if (auth()->check()) {
            $authUserId = auth()->user()->id;
        } else {
            $authUserId = null;
        }

        return Inertia::render('Users/UserShow', [
            'user' => $user,
            'authUserId' => auth()->id(),
            'csrfToken' => csrf_token(),
        ]);
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return Inertia::render('Users/EditUser', [
            'user' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return redirect()->route('users.show', $user)->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
