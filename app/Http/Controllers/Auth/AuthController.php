<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Exibe a view de login/registro.
     */
    public function showLogin(): \Illuminate\View\View
    {
        return view('auth.login');
    }

    /**
     * POST /auth/login
     */
    public function login(Request $request): JsonResponse
    {
        // TODO: refactor to service
        $request->validate([
            'email'    => ['required', 'email:rfc,dns'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'O e-mail é obrigatório.',
            'email.email'       => 'Informe um e-mail válido.',
            'password.required' => 'A senha é obrigatória.',
        ]);

        // ── Rate limiting (5 tentativas por minuto por IP+email) ──
        $throttleKey = 'login.' . Str::lower($request->email) . '.' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'message' => "Muitas tentativas. Aguarde {$seconds} segundos.",
            ], 429);
        }

        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);

            return response()->json([
                'message' => 'Credenciais inválidas.',
                'errors'  => [
                    'email' => ['E-mail ou senha incorretos.'],
                ],
            ], 422);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        return response()->json([
            'message'  => 'Login realizado com sucesso.',
            'redirect' => route('dashboard'),
        ], 200);
    }

    /**
     * POST /auth/register
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:100'],
            'email'    => ['required', 'email:rfc,dns', 'max:190', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()      // ao menos 1 maiúscula e 1 minúscula
                    ->numbers()        // ao menos 1 número
                    ->symbols()        // ao menos 1 caractere especial
                    ->uncompromised(), // verifica se não foi exposta em data leaks
            ],
        ], [
            'name.required'             => 'O nome é obrigatório.',
            'name.min'                  => 'O nome deve ter ao menos 2 caracteres.',
            'email.required'            => 'O e-mail é obrigatório.',
            'email.email'               => 'Informe um e-mail válido.',
            'email.unique'              => 'Este e-mail já está cadastrado.',
            'password.required'         => 'A senha é obrigatória.',
            'password.confirmed'        => 'As senhas não conferem.',
            'password.min'              => 'A senha deve ter ao menos 8 caracteres.',
            'password.mixed_case'       => 'A senha deve conter pelo menos uma letra maiúscula e uma minúscula.',
            'password.numbers'          => 'A senha deve conter pelo menos um número.',
            'password.symbols'          => 'A senha deve conter pelo menos um caractere especial.',
            'password.uncompromised'    => 'Esta senha foi exposta em vazamentos conhecidos. Por favor, use outra.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // bcrypt automático pelo Laravel
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'message'  => 'Conta criada com sucesso.',
            'redirect' => route('dashboard'),
        ], 201);
    }

    /**
     * POST /auth/logout
     */
    public function logout(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
