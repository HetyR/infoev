<?php

namespace App\Console\Commands;

use App\Models\AffiliateLink;
use App\Jobs\CheckAffiliateLink;
use App\Mail\InactiveAffiliateLinksReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CheckAffiliateLinks extends Command
{
    protected $signature = 'affiliate:check-links';

    protected $description = 'Check all affiliate links status and send report email for inactive links';

    public function handle()
    {
        $this->info('Memulai pengecekan affiliate link...');

        $allLinks = AffiliateLink::all();

        foreach ($allLinks as $link) {
            $this->info("Cek link: {$link->link}");
            // Dispatch job synchronous agar proses urut dan bisa lihat log di terminal
            (new CheckAffiliateLink($link))->handle();
        }

        $inactiveLinks = AffiliateLink::where('is_active', false)->get();

        if ($inactiveLinks->isEmpty()) {
            $this->info('Semua affiliate link aktif, tidak ada yang mati.');
        } else {
            $this->info('Ada affiliate link yang tidak aktif, mengirim email laporan...');

            // Kirim email laporan ke admin (ganti email admin sesuai)
            Mail::to('hetyoyi@gmail.com')->send(new InactiveAffiliateLinksReport($inactiveLinks));

            $this->info('Email laporan sudah dikirim.');
        }

        $this->info('Pengecekan selesai.');
    }
}
