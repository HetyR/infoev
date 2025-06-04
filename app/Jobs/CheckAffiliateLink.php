<?php

namespace App\Jobs;

use App\Models\AffiliateLink;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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

            // Cek platform berdasarkan domain di URL
            if (strpos($url, 'shopee') !== false) {
                $isActive = $this->checkShopeeLinkActive($url);
            } elseif (strpos($url, 'tokopedia') !== false) {
                $isActive = $this->checkTokopediaLinkActive($url);
            }

            $this->link->is_active = $isActive;
            $this->link->last_checked_at = now();
            $this->link->save();

            \Log::info('Job CheckAffiliateLink untuk URL ' . $url . ' berhasil dijalankan pada ' . now() . ' dengan status: ' . ($isActive ? 'Active' : 'Inactive'));

        } catch (\Exception $e) {
            \Log::error('Job CheckAffiliateLink untuk URL ' . $url . ' gagal dijalankan pada ' . now() . ', Error: ' . $e->getMessage());
            // Tandai link sebagai tidak aktif jika gagal
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
        curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);

        if ($httpCode >= 400) {
            return false;
        }

        if (strpos($finalUrl, 'error_page') !== false ||
            strpos($finalUrl, 'login') !== false ||
            strpos($finalUrl, 'captcha') !== false) {
            return false;
        }

        return true;
    }

    private function checkTokopediaLinkActive(string $url): bool
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0 Safari/537.36');
        curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);

        if ($httpCode >= 400) {
            return false;
        }

        if (strpos($finalUrl, 'error') !== false ||
            strpos($finalUrl, 'login') !== false ||
            strpos($finalUrl, 'not-found') !== false) {
            return false;
        }

        return true;
    }
}