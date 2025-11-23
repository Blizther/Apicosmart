<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        if(Auth::user()->rol=='usuario'){
            $iduser = Auth::id();
            $users = User::where('idusuario', $iduser)->where('estado', 1)->get();
        } else {
            $users = User::query()
                ->when($q !== '', function ($query) use ($q) {
                    $query->where(function ($sub) use ($q) {
                        $sub->where('nombre', 'LIKE', "%{$q}%")
                            ->orWhere('primerApellido', 'LIKE', "%{$q}%")
                            ->orWhere('segundoApellido', 'LIKE', "%{$q}%")
                            ->orWhere('email', 'LIKE', "%{$q}%")
                            ->orWhere('rol', 'LIKE', "%{$q}%");
                    });
                })
                ->orderBy('nombre')
                ->orderBy('primerApellido')
                ->paginate(10)
                ->appends(['q' => $q]);
        }

        return view('users.index', compact('users', 'q'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nombre'          => 'required|string|max:100',
        'primerApellido'  => 'required|string|max:100',
        'segundoApellido' => 'nullable|string|max:100',
        'email'           => 'required|email|unique:users,email',
        'telefono'        => 'required|digits:8',
        'nombreUsuario'   => 'required|string|max:50|unique:users,nombreUsuario',
        'password'        => 'required|string|min:8|confirmed',
        'rol'             => ['required', Rule::in(['usuario','administrador','colaborador'])],
    ], [
        // TELÉFONO
        'telefono.required' => 'El teléfono es obligatorio.',
        'telefono.digits'   => 'El teléfono debe contener exactamente 8 dígitos.',

        // PASSWORD
        'password.required'  => 'La contraseña es obligatoria.',
        'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'La confirmación de contraseña no coincide.',
    ]);

    $idusuario = Auth::user()->rol == 'usuario' ? Auth::id() : null;

    User::create([
        'nombre'          => $request->nombre,
        'primerApellido'  => $request->primerApellido,
        'segundoApellido' => $request->segundoApellido,
        'email'           => $request->email,
        'telefono'        => $request->telefono,
        'nombreUsuario'   => $request->nombreUsuario,
        'password'        => Hash::make($request->password),
        'rol'             => $request->rol,
        'idusuario'       => $idusuario,
    ]);

    return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
}


    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function editpermiso($id)
    {
        return view('users.permisos');
    }

    public function updatepermiso(Request $request)
    {
        return redirect()->route('users.index')->with('success', 'Permisos actualizados.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
        'nombre'          => 'required|string|max:100',
        'primerApellido'  => 'required|string|max:100',
        'segundoApellido' => 'nullable|string|max:100',
        'email'           => ['required','email', Rule::unique('users','email')->ignore($user->id)],
        'telefono'        => 'required|digits:8',
        'nombreUsuario'   => ['required','string','max:50', Rule::unique('users','nombreUsuario')->ignore($user->id)],
        'rol'             => ['required', Rule::in(['usuario','administrador','colaborador'])],
        'password'        => 'nullable|min:8|confirmed',
    ], [
        'telefono.digits' => 'El teléfono debe contener exactamente 8 dígitos.',

        'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'La confirmación de contraseña no coincide.',
    ]);

        $user->nombre          = $request->nombre;
        $user->primerApellido  = $request->primerApellido;
        $user->segundoApellido = $request->segundoApellido;
        $user->email           = $request->email;
        $user->telefono        = $request->telefono;
        $user->nombreUsuario   = $request->nombreUsuario;
        $user->rol             = $request->rol;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado.');
    }

    public function totalApiarios()
    {
        $user = Auth::user();
        $totalApiarios = User::find($user->id)->apiarios()->count();

        return response()->json(['totalApiarios' => $totalApiarios]);
    }

    public function totalColmenasActivas()
    {
        $user = Auth::user();
        $totalColmenasActivas = User::find($user->id)->colmenasActivas()->count();

        return response()->json(['totalColmenasActivas' => $totalColmenasActivas]);
    }
}
