<?php

namespace App\Jobs;

use App\Models\AffiliateLink;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class CheckAffiliateLink implements ShouldQueue
{
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // protected $link;

    // public function __construct(AffiliateLink $link)
    // {
    //     $this->link = $link;
    // }

    // public function handle()
    // {
    //     $url = $this->link->link;

    //     $isActive = $this->checkShopeeLinkActive($url);

    //     $this->link->is_active = $isActive;
    //     $this->link->last_checked_at = now();
    //     $this->link->save();
    // }

  
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $link;

    public function __construct(AffiliateLink $link)
    {
        $this->link = $link;
    }

    public function handle()
    {
        $url = $this->link->link;

        $isActive = $this->checkShopeeLinkActive($url);

        $this->link->is_active = $isActive;
        $this->link->last_checked_at = now();
        $this->link->save();
    }

    private function checkShopeeLinkActive(string $url): bool
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $error = curl_error($ch);
        curl_close($ch);

        // Log untuk debugging
        Log::info("cURL result untuk $url - HTTP Code: $httpCode, Final URL: $finalUrl");
        if ($error) {
            Log::error("cURL error untuk $url: $error");
            return false;
        }

        // Cek status kode HTTP, redirect, atau teks error
        if ($httpCode >= 400 ||
            strpos($finalUrl, 'error_page') !== false ||
            strpos($finalUrl, 'login') !== false ||
            strpos($finalUrl, 'captcha') !== false ||
            stripos($response, 'Produk tidak ada') !== false ||
            stripos($response, 'product-not-exist__text') !== false) {
            Log::info("Link tidak aktif: $url");
            return false;
        }

        Log::info("Link aktif: $url");
        return true;
    }
}
