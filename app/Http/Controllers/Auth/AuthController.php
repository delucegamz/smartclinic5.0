<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Employee;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Routing\Redirector;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Change default username from email address to username
     */
    protected $username = 'username';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware( $this->guestMiddleware(), ['except' => 'logout'] );
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator( array $data )
    {
        $validator = Validator::make( $data, [
            'id_karyawan' => 'bail|required|max:10|unique:m_pengguna',
            'username' => 'bail|required|max:25|unique:m_pengguna',
            'password' => 'required|min:5|confirmed',
            'email' => 'bail|email|max:255|unique:m_pengguna'
        ] );

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create( array $data )
    {   
        $latest_id = User::generate_id();

        $user_created = User::create( [
            'idpengguna' => $latest_id,
            'id_karyawan' => $data['id_karyawan'],
            'username' => $data['username'],
            'password' => bcrypt( $data['password'] ),
            'email' => $data['email'],
            'status' => 1
        ] ); 

        return $user_created;
    }
}
