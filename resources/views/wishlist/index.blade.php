<div class="container mx-auto">
    <h2 class="text-2xl font-bold my-4">Keranjang</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar Kendaraan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kendaraan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($informasiKendaraan as $kendaraan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ $kendaraan['gambar'] }}" class="h-16 w-16 object-cover rounded-md shadow-md" alt="Gambar Kendaraan">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $kendaraan['merek'] }} {{ $kendaraan['nama'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                            <a href="#" class="text-red-600 hover:text-red-900">Hapus</a>
                            <a href="#" class="text-blue-600 hover:text-blue-900">Lihat</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
