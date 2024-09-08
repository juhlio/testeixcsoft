<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'documento' => [
                'required',
                'string',
                'max:18',
                Rule::unique('users', 'documento'),
                function ($attribute, $value, $fail) {
                    $value = preg_replace('/\D/', '', $value); // Remove non-numeric characters
                    if (!$this->isValidCpfOrCnpj($value)) {
                        $fail(__('validation.custom.documento_invalid'));
                    }
                },
            ],
            'tipo' => ['required', 'in:fisica,juridica'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'documento.required' => 'O campo documento é obrigatório.',
            'documento.unique' => 'O documento fornecido já está cadastrado.',
            'documento.custom.documento_invalid' => 'O documento fornecido é inválido.',
            'tipo.required' => 'O tipo é obrigatório.',
            'tipo.in' => 'O tipo deve ser "fisica" ou "juridica".',
            'password.confirmed' => 'A confirmação da senha não confere.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'documento' => $data['documento'],
            'tipo' => $data['tipo'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Validate CPF or CNPJ.
     *
     * @param  string  $value
     * @return bool
     */
    protected function isValidCpfOrCnpj($value)
    {
        // Verifica se é CPF
        if (strlen($value) === 11) {
            return $this->isValidCpf($value);
        }

        // Verifica se é CNPJ
        if (strlen($value) === 14) {
            return $this->isValidCnpj($value);
        }

        return false;
    }

    /**
     * Validate CPF.
     *
     * @param  string  $cpf
     * @return bool
     */
    protected function isValidCpf($cpf)
    {
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false; // CPF com todos os dígitos iguais (ex: 11111111111)
        }

        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        $sum = 0;
        $weight = [10, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 9; $i++) {
            $sum += $cpf[$i] * $weight[$i];
        }
        $remainder = $sum % 11;
        $digit1 = ($remainder < 2) ? 0 : 11 - $remainder;

        if ($cpf[9] != $digit1) {
            return false;
        }

        $sum = 0;
        $weight = [11, 10, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 10; $i++) {
            $sum += $cpf[$i] * $weight[$i];
        }
        $remainder = $sum % 11;
        $digit2 = ($remainder < 2) ? 0 : 11 - $remainder;

        return $cpf[10] == $digit2;
    }

    /**
     * Validate CNPJ.
     *
     * @param  string  $cnpj
     * @return bool
     */
    protected function isValidCnpj($cnpj)
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);

        if (strlen($cnpj) != 14) {
            return false;
        }

        $sum = 0;
        $weight = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 12; $i++) {
            $sum += $cnpj[$i] * $weight[$i];
        }
        $remainder = $sum % 11;
        $digit1 = ($remainder < 2) ? 0 : 11 - $remainder;

        if ($cnpj[12] != $digit1) {
            return false;
        }

        $sum = 0;
        $weight = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 13; $i++) {
            $sum += $cnpj[$i] * $weight[$i];
        }
        $remainder = $sum % 11;
        $digit2 = ($remainder < 2) ? 0 : 11 - $remainder;

        return $cnpj[13] == $digit2;
    }
}
