<div class="container mx-auto">
    <h2 class="text-2xl font-bold my-4">Profil Pengguna</h2>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="mb-4">
            <strong>Nama:</strong> {{ $user->name }}
        </div>
        <div class="mb-4">
            <strong>Email:</strong> {{ $user->email }}
        </div>
    </div>
</div>
