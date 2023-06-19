<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request) : View
    {   
        if (Auth::user()->isAdmin()) {
            $query = User::query();
            $search = $request->input('search');
    
            if ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            }
            
            $query->whereIn('user_type', ['A', 'E']); 

            $users = $query->paginate(15);

            return view('users.index', compact('users'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para ver utilizadores!')->with('alert-type', 'danger');
        }
    }

    public function create(): View
    {
        if(Auth::user()->isAdmin()) {
            $user = new User();
            return view('users.create', compact('user'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para criar utilizadores!')->with('alert-type', 'danger');
        }
    }

    public function show(user $user) : View
    {
        if (Auth::user()->isAdmin()) {
            return view('users.show', compact('user'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para ver utilizadores!')->with('alert-type', 'danger');
        }
    }

    public function edit(user $user): View
    {
        if (Auth::user()->isAdmin()) {
            return view('users.edit', compact('user'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para ver utilizadores!')->with('alert-type', 'danger');
        }
    }

    public function update(Request $request, user $user): RedirectResponse
    {
        if (Auth::user()->isAdmin()) {
            $user->update($request->all());
            return redirect()->route('users.show')
                ->with('alert-msg', 'Utlizador atualizado com sucesso!')
                ->with('alert-type', 'success');
        } else {
            return redirect()->route('home')->with('alert-msg', 'Não tem permissões para ver utilizadores!')->with('alert-type', 'danger');
        }
    }

    public function store(Request $request): RedirectResponse
    {
        if (Auth::user()->isAdmin()) {
            user::create($request->all());
            return redirect()->route('users.index')
                ->with('alert-msg', 'Utlizador criado com sucesso!')
                ->with('alert-type', 'success');
        } else {
            return redirect()->route('home')->with('alert-msg', 'Não tem permissões para criar utilizadores!')->with('alert-type', 'danger');
        }
    }

    public function destroy(user $user): RedirectResponse
    {
        if (Auth::user()->isAdmin()) {
            $user->delete();
            return redirect()->route('users.index')
                ->with('alert-msg', 'Utlizador eliminado com sucesso!')
                ->with('alert-type', 'success');
        } else {
            return redirect()->route('home')->with('alert-msg', 'Não tem permissões para eliminar utilizadores!')->with('alert-type', 'danger');
        }
    }
}
