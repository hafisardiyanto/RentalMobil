<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\SendPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Traits\WhatsappTrait;

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
        
        // Generate password baru secara acak
        $newPassword = Str::random(8);
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        // KIRIM PASSWORD BARU VIA WHATSAPP
        $resetMessage = "*[LUPA PASSWORD]*\n\n"
            . "Halo " . $user->name . ",\n"
            . "Kami menerima permintaan pengaturan ulang kata sandi untuk akun Anda.\n\n"
            . "Berikut adalah password sementara Anda:\n"
            . "*Password Baru: " . $newPassword . "*\n\n"
            . "Segera login dan ubah password Anda di halaman profil demi keamanan. Terima kasih.";

        if ($user->phone) {
            $this->sendWhatsapp($user->phone, $resetMessage);
            return back()->with('success', 'Password baru berhasil dikirim ke WhatsApp Anda.');
        }

        return back()->with('error', 'Gagal mengirim WhatsApp. Pastikan nomor telpon Anda sudah benar.');
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
