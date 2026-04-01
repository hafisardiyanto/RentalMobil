<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait WhatsappTrait
{
    /**
     * Membersihkan format nomor telepon agar sesuai standar Internasional (62)
     */
    public function formatNumber($number)
    {
        if (!$number) return null;
        
        // Hapus karakter non-digit
        $number = preg_replace('/[^0-9]/', '', $number);
        
        // Cek jika dimulai dengan 0, ganti jadi 62
        if (strpos($number, '0') === 0) {
            $number = '62' . substr($number, 1);
        }
        
        // Jika sudah dimulai dengan 81... tambahkan 62
        if (strpos($number, '8') === 0) {
            $number = '62' . $number;
        }

        return $number;
    }

    /**
     * Mengirim pesan via Fonnte API
     */
    public function sendWhatsapp($target, $message)
    {
        $token = config('services.fonnte.token');
        $targetFormatted = $this->formatNumber($target);

        if ($token && $targetFormatted) {
            try {
                $response = Http::withoutVerifying()->withHeaders([
                    'Authorization' => $token,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $targetFormatted,
                    'message' => $message,
                ]);

                // Log response if failed to send
                if (!$response->successful()) {
                    \Log::error("Fonnte failed to send: " . $response->body());
                }

                return $response;
            } catch (\Exception $e) {
                \Log::error("Fonnte Exception: " . $e->getMessage());
                return false;
            }
        } else {
            if (!$token) \Log::warning("Fonnte Token is missing in config.");
            if (!$targetFormatted) \Log::warning("Fonnte Target Number is empty or invalid.");
        }
        return false;
    }
}
