<?php

namespace App\Jobs;

use App\Models\AffiliateLink;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk logging

class CheckAffiliateLink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $link;

    public function __construct(AffiliateLink $link)
    {
        $this->link = $link;
    }

    public function handle()
    {
        try {
            $url = $this->link->link;
            $isActive = false;

            Log::info('Memulai pemeriksaan link: ' . $url);

            // Cek platform berdasarkan domain di URL
            if (strpos($url, 'shopee') !== false) {
                $isActive = $this->checkShopeeLinkActive($url);
            } elseif (strpos($url, 'tokopedia') !== false) {
                $isActive = $this->checkTokopediaLinkActive($url);
            } else {
                Log::warning('Platform tidak dikenali untuk URL: ' . $url);
            }

            $this->link->is_active = $isActive;
            $this->link->last_checked_at = now();
            $this->link->save();

            Log::info('Job CheckAffiliateLink untuk URL ' . $url . ' berhasil dijalankan pada ' . now() . ' dengan status: ' . ($isActive ? 'Active' : 'Inactive'));
        } catch (\Exception $e) {
            Log::error('Job CheckAffiliateLink untuk URL ' . $url . ' gagal dijalankan pada ' . now() . ', Error: ' . $e->getMessage());
            $this->link->is_active = false;
            $this->link->last_checked_at = now();
            $this->link->save();
        }
    }

    private function checkShopeeLinkActive(string $url): bool
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0 Safari/537.36');
        $response = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $error = curl_error($ch);
        curl_close($ch);

        Log::info("Shopee URL: {$url}, HTTP Code: {$httpCode}, Final URL: {$finalUrl}, Error: " . ($error ?: 'None'));

        if ($error) {
            Log::error("cURL error untuk Shopee URL: {$url}, Error: {$error}");
            return false;
        }

        if ($httpCode >= 400) {
            Log::info("Shopee URL: {$url} tidak aktif (HTTP Code: {$httpCode})");
            return false;
        }

        if (strpos($finalUrl, 'error_page') !== false || strpos($finalUrl, 'login') !== false || strpos($finalUrl, 'captcha') !== false) {
            Log::info("Shopee URL: {$url} tidak aktif (Redirect ke: {$finalUrl})");
            return false;
        }

        Log::info("Shopee URL: {$url} aktif");
        return true;
    }

    private function checkTokopediaLinkActive(string $url): bool
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36'); // Update user-agent

        // Tambahkan konfigurasi SOCKS5 proxy
        curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:1080'); // ganti sesuai alamat proxy kamu
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5_HOSTNAME); // gunakan SOCKS5 dengan resolve hostname di proxy

        $response = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $error = curl_error($ch);
        curl_close($ch);

        Log::info("Tokopedia URL: {$url}, HTTP Code: {$httpCode}, Final URL: {$finalUrl}, Error: " . ($error ?: 'None'));

        // Pengecualian untuk URL tidak valid (hanya domain atau kosong)
        if (empty(parse_url($url, PHP_URL_PATH)) || $url === 'tokopedia.com') {
            Log::warning("Tokopedia URL tidak valid: {$url}");
            return false;
        }

        if ($error) {
            Log::error("cURL error untuk Tokopedia URL: {$url}, Error: {$error}");
            return false;
        }

        if ($httpCode >= 400) {
            Log::info("Tokopedia URL: {$url} tidak aktif (HTTP Code: {$httpCode})");
            return false;
        }

        // Perbaikan logika redirect untuk Tokopedia
        if (strpos($finalUrl, 'error') !== false || strpos($finalUrl, 'login') !== false || strpos($finalUrl, 'not-found') !== false || strpos($finalUrl, 'blocked') !== false) {
            // Tambahan untuk deteksi blok
            Log::info("Tokopedia URL: {$url} tidak aktif (Redirect ke: {$finalUrl})");
            return false;
        }

        Log::info("Tokopedia URL: {$url} aktif");
        return true;
    }
}
