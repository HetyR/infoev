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
        $url = $this->link->link;

        $isActive = $this->checkShopeeLinkActive($url);

        $this->link->is_active = $isActive;
        $this->link->last_checked_at = now();
        $this->link->save();
    }

    private function checkShopeeLinkActive(string $url): bool
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set user-agent biar request mirip browser
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0 Safari/537.36');
        curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);

        // Kalau status http error 400 ke atas, link dianggap mati
        if ($httpCode >= 400) {
            return false;
        }

        // Jika redirect ke halaman error shopee, login, captcha, anggap mati
        if (strpos($finalUrl, 'error_page') !== false
            || strpos($finalUrl, 'login') !== false
            || strpos($finalUrl, 'captcha') !== false) {
            return false;
        }

        return true;
    }
}
