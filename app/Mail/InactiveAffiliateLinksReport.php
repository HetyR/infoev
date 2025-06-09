<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InactiveAffiliateLinksReport extends Mailable
{
    use Queueable, SerializesModels;

    public $inactiveLinks;

    public function __construct($inactiveLinks)
    {
        $this->inactiveLinks = $inactiveLinks;
    }

    public function build()
    {
        return $this->subject('Laporan Affiliate Link Tidak Aktif')
                    ->view('emails.inactive_affiliate_links')
                    ->with([
                        'inactiveLinks' => $this->inactiveLinks,
                    ]);
    }
}
