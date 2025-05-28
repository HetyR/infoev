<?php

namespace App\Jobs;

use App\Models\AffiliateLink;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckAffiliateLink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected AffiliateLink $affiliateLink;

    public function __construct(AffiliateLink $affiliateLink)
    {
        $this->affiliateLink = $affiliateLink;
    }

    public function handle()
    {
        $url = $this->affiliateLink->link;
        $foundInvalid = false;

        $host = parse_url($url, PHP_URL_HOST);

        try {
            if ($host && str_contains($host, 'shopee.co.id')) {
                // Panggil node script Shopee
                $output = [];
                exec("node " . base_path('node-checker/checkShopee.js') . " " . escapeshellarg($url), $output);
                $result = trim(end($output));
                if ($result === 'inactive') {
                    $foundInvalid = true;
                    Log::warning("Shopee link dianggap mati: {$url}");
                }
            } elseif ($host && (str_contains($host, 'tokopedia.com') || str_contains($host, 'tk.tokopedia.com'))) {
                // Panggil node script Tokopedia
                $output = [];
                exec("node " . base_path('node-checker/checkTokopedia.js') . " " . escapeshellarg($url), $output);
                $result = trim(end($output));
                if ($result === 'inactive') {
                    $foundInvalid = true;
                    Log::warning("Tokopedia link dianggap mati: {$url}");
                }
            } else {
                // Default check via HTTP dengan header User-Agent
                $response = Http::timeout(15)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ' .
                                        'AppleWebKit/537.36 (KHTML, like Gecko) ' .
                                        'Chrome/114.0.0.0 Safari/537.36',
                        'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
                    ])
                    ->get($url);

                $status = $response->status();
                $body = $response->body();

                if ($status !== 200) {
                    $foundInvalid = true;
                }

                $invalidIndicators = [
                    'Halaman tidak ditemukan',
                    'produk sudah tidak tersedia',
                    'Produk sudah tidak tersedia',
                ];

                foreach ($invalidIndicators as $keyword) {
                    if (stripos($body, $keyword) !== false) {
                        $foundInvalid = true;
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Gagal cek link: " . $e->getMessage() . " untuk {$url}");
            $foundInvalid = true;
        }

        $this->affiliateLink->is_active = !$foundInvalid;
        $this->affiliateLink->last_checked_at = now();
        $this->affiliateLink->save();
    }
}
