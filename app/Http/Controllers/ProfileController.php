<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * GET /profile
     */
    public function edit(): View
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    /**
     * POST /profile/update  - atualiza nome e e-mail
     */
    public function update(Request $request): JsonResponse
    {
        $user = Auth::user();

        $request->validate([
            'name'  => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:190', "unique:users,email,{$user->id}"],
        ], [
            'name.required'  => 'O nome e obrigatorio.',
            'name.min'       => 'O nome deve ter ao menos 2 caracteres.',
            'email.required' => 'O e-mail e obrigatorio.',
            'email.email'    => 'Informe um e-mail valido.',
            'email.unique'   => 'Este e-mail ja esta em uso.',
        ]);

        // Marca e-mail como nao verificado se for alterado
        if ($user->email !== $request->email) {
            $user->email_verified_at = null;
        }

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'message' => 'Perfil atualizado com sucesso.',
            'user'    => ['name' => $user->name, 'email' => $user->email],
        ]);
    }


    /**
     * POST /profile/avatar  - upload de foto de perfil
     */
    public function updateAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,png,webp', 'max:2048', 'dimensions:min_width=50,min_height=50,max_width=4000,max_height=4000'],
        ]);

        // , [
        //     'avatar.required'   => 'Selecione uma imagem.',
        //     'avatar.image'      => 'O arquivo deve ser uma imagem.',
        //     'avatar.mimes'      => 'Formatos aceitos: JPG, PNG, WebP.',
        //     'avatar.max'        => 'A imagem deve ter no maximo 2 MB.',
        //     'avatar.dimensions' => 'Dimensoes invalidas (min. 50x50, max. 4000x4000).',
        // ]

        $user = Auth::user();

        // Remove avatar anterior do disco
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Salva o novo arquivo em storage/app/public/avatars/
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->avatar = $path;
        $user->save();

        return response()->json([
            'message' => 'Foto atualizada com sucesso.',
            'url'     => asset("storage/{$path}"),
        ]);
    }

    /**
     * POST /profile/avatar/remove  - remove foto de perfil
     */
    public function removeAvatar(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = null;
        $user->save();

        return response()->json(['message' => 'Foto removida.']);
    }

    /**
     * POST /profile/password  - altera a senha
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'current_password.required' => 'Informe sua senha atual.',
            'password.required'         => 'Informe a nova senha.',
            'password.confirmed'        => 'As senhas nao conferem.',
            'password.min'              => 'A senha deve ter ao menos 8 caracteres.',
        ]);

        // Verifica a senha atual
        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Senha atual incorreta.',
                'errors'  => ['current_password' => ['Senha atual incorreta.']],
            ], 422);
        }

        // Impede reutilizacao da senha atual
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'A nova senha nao pode ser igual a atual.',
                'errors'  => ['password' => ['A nova senha nao pode ser igual a atual.']],
            ], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Senha alterada com sucesso.']);
    }

    /**
     * POST /profile/destroy  - exclui a conta
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ], ['password.required' => 'Informe sua senha para confirmar.']);

        $user = Auth::user();

        if (! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Senha incorreta.'], 422);
        }

        // Remove avatar
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['redirect' => route('login')]);
    }
}
