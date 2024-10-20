<?php

namespace App\Http\Controllers\Anthaleja\Frontier;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class FrontierController extends Controller
{
    // Mostra il form di login
    public function showLoginForm()
    {
        return view('anthaleja.frontier.login');
    }

    // Effettua il login
    public function login(Request $request)
    {
        // Validazione dei dati del login
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Verifica le credenziali
        if (Auth::attempt($request->only('username', 'password'))) {
            // Reindirizza alla home se il login ha successo
            return redirect()->route('home');
        }

        // Se le credenziali sono errate, ritorna con un errore
        throw ValidationException::withMessages([
            'username' => ['Le credenziali non sono corrette.'],
        ]);
    }

    // Mostra il form di registrazione
    public function showRegisterForm()
    {
        return view('anthaleja.frontier.register');
    }

    // Effettua la registrazione
    public function register(Request $request)
    {
        // Validazione dei dati della registrazione
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Creazione del nuovo utente
        $user = User::create([
            'username' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Effettua il login dell'utente appena registrato
        Auth::login($user);

        // Reindirizza alla home dopo la registrazione
        return redirect()->route('home');
    }

    // Effettua il logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function generateApiToken(Request $request)
    {
        $character = Auth::user()->character;

        // Genera un token univoco
        $token = Str::random(60);

        // Assegna e salva il token nel character
        $character->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        return response()->json(['token' => $token]);
    }
}
