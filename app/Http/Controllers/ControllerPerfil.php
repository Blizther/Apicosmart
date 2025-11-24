<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ControllerPerfil extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nombre'          => ['required','string','max:255'],
            'primerApellido'  => ['nullable','string','max:255'],
            'segundoApellido' => ['nullable','string','max:255'],
            'email'           => [
                'required','email','max:255',
                Rule::unique('users','email')->ignore($user->id)
            ],
            'telefono'        => ['nullable','string','max:30'],

            // 🔐 contraseña actual: solo se exige si se quiere cambiar password
            'current_password' => [
                'nullable',
                'required_with:password',
                'string',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('La contraseña actual es incorrecta.');
                    }
                }
            ],

            // 🔐 nueva contraseña: solo se exige si mandan current_password
            'password' => [
                'nullable',
                'required_with:current_password',
                'string',
                'min:6',
                'confirmed'
            ],
        ],[
            'email.unique'                  => 'Ese correo ya está registrado.',
            'current_password.required_with'=> 'Debes escribir tu contraseña actual para cambiarla.',
            'password.required_with'        => 'Debes escribir una nueva contraseña.',
            'password.confirmed'            => 'La confirmación de contraseña no coincide.',
            'password.min'                  => 'La nueva contraseña debe tener al menos 6 caracteres.',
        ]);

        // ✅ Actualizar solo datos permitidos
        $user->nombre          = $request->nombre;
        $user->primerApellido  = $request->primerApellido;
        $user->segundoApellido = $request->segundoApellido;
        $user->email           = $request->email;
        $user->telefono        = $request->telefono;

        // ✅ Actualizar contraseña SOLO si la mandaron
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
