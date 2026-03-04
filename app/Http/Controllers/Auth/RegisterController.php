<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $institutions = \App\Models\Institution::where('is_active', true)->orderBy('name')->get();
        return view('auth.register', compact('institutions'));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name'     => ['required', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'       => ['required', 'string', 'min:8', 'confirmed'],
            'institution_id' => ['required', 'exists:institutions,id'],
            'terms'          => ['required'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'first_name'      => $data['first_name'],
            'last_name'       => $data['last_name'],
            'email'           => $data['email'],
            'password'        => bcrypt($data['password']),
            'role'            => 'user',
            'institution_id'  => $data['institution_id'],
        ]);
    }
}
