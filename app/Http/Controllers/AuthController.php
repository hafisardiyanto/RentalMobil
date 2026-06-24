<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\SendPasswordMail;
use Illuminate\Support\Facades\Mail;
use App\Traits\WhatsappTrait;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    use WhatsappTrait;
    public function showLogin()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function processRegister(Request $request)
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

        // KIRIM EMAIL NOTIFIKASI REGISTRASI
        try {
            Mail::to($user->email)->send(new SendPasswordMail($user, $request->password));
        } catch (\Exception $e) {
            \Log::error("Gagal mengirim email registrasi: " . $e->getMessage());
        }

        // Kirim data ke halaman sukses via Session (tanpa WhatsApp otomatis)
        return redirect()->route('register.success')->with([
            'success_name' => $user->name,
            'success_email' => $user->email,
            'success_password' => $request->password, // Password asli dari input
        ]);
    }

    public function showRegisterSuccess()
    {
        // Pastikan halaman ini hanya bisa diakses setelah registrasi (cek session)
        if (!session('success_email')) {
            return redirect()->route('register');
        }
        return view('auth.register-success');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function processForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        $token = Str::random(64);

        // Simpan token ke tabel password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);

        // KIRIM EMAIL LINK RESET
        try {
            Mail::to($user->email)->send(new ResetPasswordMail($user, $resetUrl));
            return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            \Log::error("Gagal mengirim email reset: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengirim email.');
        }
    }

    public function showResetPassword($token, Request $request)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function processResetPassword(Request $request)
    {
        \Log::info("Memulai proses reset password untuk: " . $request->email);

        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:6|confirmed',
            ]);

            // Verifikasi token
            $reset = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->first();

            if (!$reset || $reset->token !== $request->token) {
                \Log::warning("Token tidak cocok atau tidak ditemukan untuk: " . $request->email);
                return back()->with('error', 'Token reset password tidak valid atau sudah kedaluwarsa.');
            }

            // Cek kedaluwarsa (misal 60 menit)
            if (Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
                \Log::warning("Token kedaluwarsa untuk: " . $request->email);
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();
                return back()->with('error', 'Token reset password sudah kedaluwarsa.');
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                \Log::error("User tidak ditemukan saat reset password: " . $request->email);
                return back()->with('error', 'User tidak ditemukan.');
            }

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            \Log::info("Password berhasil diupdate untuk: " . $request->email);

            // Hapus token setelah digunakan
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            // KIRIM KONFIRMASI VIA WHATSAPP (Password Baru)
            $message = "*[RESET PASSWORD BERHASIL]*\n\n"
                . "Halo " . $user->name . ",\n"
                . "Password akun RentalMobil Anda telah berhasil diubah.\n\n"
                . "Berikut adalah password baru Anda:\n"
                . "*Password: " . $request->password . "*\n\n"
                . "Jika Anda tidak merasa melakukan ini, segera hubungi admin. Terima kasih.";

            if ($user->phone) {
                \Log::info("Mengirim WA konfirmasi ke: " . $user->phone);
                $this->sendWhatsapp($user->phone, $message);
            } else {
                \Log::warning("User tidak punya nomor HP untuk dikirimi WA: " . $request->email);
            }

            return redirect()->route('login')->with('success', 'Password berhasil diubah. Konfirmasi telah dikirim ke WhatsApp Anda.');

        } catch (\Exception $e) {
            \Log::error("Eror saat processResetPassword: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda telah keluar.');
    }

    // Google Login
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if (!$user) {
                // User Baru: Generate Password Acak
                $randomPassword = Str::random(10);
                
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make($randomPassword),
                    'role' => 'user',
                ]);

                // KIRIM EMAIL PASSWORD KE USER
                try {
                    Mail::to($user->email)->send(new SendPasswordMail($user, $randomPassword));
                } catch (\Exception $e) {
                    \Log::error("Gagal mengirim email: " . $e->getMessage());
                }
            } else {
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
            }

            Auth::login($user);
            return redirect()->route('home')->with('success', 'Berhasil masuk dengan Google!');

        } catch (\Exception $e) {
            \Log::error("Google Login Error: " . $e->getMessage());
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login Google.');
        }
    }
}
