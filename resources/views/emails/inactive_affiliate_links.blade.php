<h1>Laporan Affiliate Link Tidak Aktif</h1>

<p>Berikut daftar link affiliate yang sudah tidak aktif:</p>

<ul>
    @foreach ($inactiveLinks as $link)
        <li>
            Kendaraan: {{ $link->vehicle->name ?? 'N/A' }} <br>
            Marketplace: {{ $link->marketplace->name ?? 'N/A' }} <br>
            Link: <a href="{{ $link->link }}">{{ $link->link }}</a> <br>
            Terakhir dicek: {{ $link->last_checked_at ?? 'Belum pernah dicek' }}
        </li>
    @endforeach

</ul>
