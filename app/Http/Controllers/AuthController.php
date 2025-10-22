<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Empleado;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'clave' => 'required',
        ]);

        $user = User::where('correo', $request->correo)->first();

        if ($user && Hash::check($request->clave, $user->clave)) {
            if ($user->estado == 1) {
                Auth::login($user);

                $isCliente = $user->cliente ? true : false;
                $isEmpleado = $user->empleado ? true : false;

                if ($isCliente && $isEmpleado) {
                    Session::put('idcliente', $user->cliente->idcliente);
                    Session::put('idempleado', $user->empleado->idempleado);
                    Session::put('idrol', $user->empleado->idrol);

                    return view('auth.select-role');
                } elseif ($isCliente) {
                    Session::put('idcliente', $user->cliente->idcliente);
                    $this->limpiarSesionesEmpleado();
                    return redirect()->route('index');
                } elseif ($isEmpleado) {
                    Session::put('idempleado', $user->empleado->idempleado);
                    Session::put('idrol', $user->empleado->idrol);
                    $this->limpiarSesionesCliente();
                    return redirect()->route('default');
                } else {
                    return back()->withErrors([
                        'correo' => 'No tienes un rol asignado.',
                    ]);
                }
            } else {
                return back()->withErrors([
                    'correo' => 'Tu cuenta está desactivada.',
                ]);
            }
        }

        return back()->withErrors([
            'correo' => 'Las credenciales proporcionadas no son correctas.',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido_p' => 'required|string|max:100',
            'apellido_m' => 'nullable|string|max:100',
            'telefono' => 'required|string|max:20',
            'documento' => 'required|string|max:50',
            'correo' => 'required|email|unique:usuario,correo',
            'clave' => 'required|string|min:4|confirmed',
        ]);

        try {
            
            $user = User::create([
                'nombre' => $request->nombre,
                'apellido_p' => $request->apellido_p,
                'apellido_m' => $request->apellido_m ?? '',
                'telefono' => $request->telefono,
                'documento' => $request->documento,
                'correo' => $request->correo,
                'clave' => Hash::make($request->clave),
                'estado' => 1,
                'fecharegistro' => now(),
            ]);

            
            Cliente::create([
                'idusuario' => $user->idusuario,
            ]);

            return redirect()->route('login')->with('success', 'Cuenta creada exitosamente. Por favor, inicia sesión.');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Ocurrió un error al crear la cuenta: ' . $e->getMessage(),
            ])->withInput();
        }
    }

    public function selectRole(Request $request)
    {
        $role = $request->role;

        if ($role == 'cliente') {
            $this->limpiarSesionesEmpleado();
            return redirect()->route('index');
        } elseif ($role == 'empleado') {
            $this->limpiarSesionesCliente();
            return redirect()->route('default');
        }

        return back();
    }

    private function limpiarSesionesCliente()
    {
        Session::forget('idcliente');
    }

    private function limpiarSesionesEmpleado()
    {
        Session::forget('idempleado');
        Session::forget('idrol');
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('login');
    }
}