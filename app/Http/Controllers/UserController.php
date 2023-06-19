<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request) : View
    {   
        $query = User::query();
        $search = $request->input('search');

        if ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }
        
        $query->whereIn('user_type', ['A', 'E']); 

        $users = $query->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $user = new User();
        return view('users.create', compact('user'));
    }

    public function show(user $user) : View
    {
        return view('users.show', compact('user'));
    }

    public function edit(user $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, user $user): RedirectResponse
    {
        $user->update($request->all());
        return redirect()->route('users.show', compact('user'))
            ->with('alert-msg', 'Utlizador atualizado com sucesso!')
            ->with('alert-type', 'success');
    }

    public function store(Request $request): RedirectResponse
    {
        user::create($request->all());
        return redirect()->route('users.index')
            ->with('alert-msg', 'Utlizador criado com sucesso!')
            ->with('alert-type', 'success');
    }

    public function destroy(user $user): RedirectResponse
{
        $user->delete();
        return redirect()->route('users.index')
            ->with('alert-msg', 'Utlizador eliminado com sucesso!')
            ->with('alert-type', 'success');
    }
}
