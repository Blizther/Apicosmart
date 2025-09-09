<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'primerApellido' => 'required|string|max:100',
            'segundoApellido' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'required|string|max:20',
            'nombreUsuario' => 'required|string|max:50|unique:users,nombreUsuario',
            'password' => 'required|string|min:8|confirmed',
            'rol' => ['required', Rule::in(['usuario', 'administrador'])], // <-- añadir
        ]);


        User::create([
            'nombre' => $request->nombre,
            'primerApellido' => $request->primerApellido,
            'segundoApellido' => $request->segundoApellido,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'nombreUsuario' => $request->nombreUsuario,
            'password' => Hash::make($request->password),
            'rol' => $request->rol, // <-- añadir
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nombre'          => 'required|string|max:100',
            'primerApellido'  => 'required|string|max:100',
            'segundoApellido' => 'required|string|max:100',
            'email'           => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'telefono'        => 'required|string|max:20',
            'nombreUsuario'   => ['required', 'string', 'max:50', Rule::unique('users', 'nombreUsuario')->ignore($user->id)],
            'rol'             => ['required', Rule::in(['usuario', 'administrador'])],
            'password'        => 'nullable|min:6',
        ]);

        $user->nombre          = $request->nombre;
        $user->primerApellido  = $request->primerApellido;
        $user->segundoApellido = $request->segundoApellido;
        $user->email           = $request->email;
        $user->telefono        = $request->telefono;
        $user->nombreUsuario   = $request->nombreUsuario;
        $user->rol             = $request->rol; // <- importante

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado.');
    }
}
