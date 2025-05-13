<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{

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

        if (auth()->check()) {
            $authUserId = auth()->user()->id;
        } else {
            $authUserId = null;
        }
        return Inertia::render('Users/UserShow', [
            'user' => $user,
            'authUserId' => $authUserId,
        ]);
    }

    public function edit(User $user)
    {
        if (auth()->user()->id !== $user->id) {
            return redirect()->route('users.index')->with('error', 'No tienes permiso para editar este usuario.');
        }

        return Inertia::render('Users/EditUser', [
            'user' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        dd($request->all());
        if (auth()->user()->id !== $user->id) {
            return redirect()->route('users.index')->with('error', 'No tienes permiso para editar este usuario.');
        }

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
        if (auth()->user()->id !== $user->id) {
            return redirect()->route('users.index')->with('error', 'No tienes permiso para eliminar este usuario.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
