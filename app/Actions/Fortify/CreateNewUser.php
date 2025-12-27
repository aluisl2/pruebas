<?php

namespace App\Actions\Fortify;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Validation\Rule;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            'tipousr' => ['required', Rule::in(['PROFESOR', 'ALUMNO'])],
            'apellido_paterno' => ['required', 'string', 'max:255'],
            'apellido_materno' => ['nullable', 'string', 'max:255'],
            'matricula' => ['required', 'regex:/^\d{10}$/', 'unique:users'],
        ])->validate();
        $rol = Rol::query()->where('clave','=',$input['tipousr'])->firstorfail();
        return User::create([
            'nombre' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'rol_id' => $rol->id,
            'apellido_paterno' => $input['apellido_paterno'],
            'apellido_materno' => $input['apellido_materno'],
            'matricula' => $input['matricula'],
        ]);
    }
}
