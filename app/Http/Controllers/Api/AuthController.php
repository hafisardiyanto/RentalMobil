<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\SendPasswordMail;
use App\Mail\ResetPasswordMail;
use App\Mail\PasswordChangedMail;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password yang Anda masukkan salah.'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        // Kirim email registrasi default
        try {
            Mail::to($user->email)->send(new SendPasswordMail($user, $request->password));
        } catch (\Exception $e) {
            \Log::error("Gagal mengirim email registrasi: " . $e->getMessage());
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi berhasil',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        $resetUrl = env('APP_URL') . '/reset-password/' . $token . '?email=' . urlencode($user->email);

        try {
            Mail::to($user->email)->send(new ResetPasswordMail($user, $resetUrl));
            return response()->json([
                'status' => 'success',
                'message' => 'Link reset password telah dikirim ke email Anda.'
            ]);
        } catch (\Exception $e) {
            \Log::error("Gagal mengirim email reset API: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengirim email.'
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset || $reset->token !== $request->token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token reset password tidak valid atau sudah kedaluwarsa.'
            ], 400);
        }

        if (Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return response()->json([
                'status' => 'error',
                'message' => 'Token reset password sudah kedaluwarsa.'
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        try {
            Mail::to($user->email)->send(new PasswordChangedMail($user));
        } catch (\Exception $e) {
            \Log::error("Gagal mengirim email konfirmasi password API: " . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Password berhasil diubah. Konfirmasi telah dikirim ke email Anda.'
        ]);
    }
}
