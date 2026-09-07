@extends('layouts.admin')

@section('title', 'Kamus Leksikon Manado')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="space-y-6">
        <!-- Form Tambah Leksikon -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-fit">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tambah Kata Baru</h3>
            <form action="{{ route('admin.leksikon.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="kata_manado" class="block text-sm font-medium text-slate-700">Kata Manado (Dialek)</label>
                        <input type="text" name="kata_manado" id="kata_manado" required placeholder="Contoh: nda" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border py-2 px-3">
                    </div>
                    <div>
                        <label for="kata_baku" class="block text-sm font-medium text-slate-700">Kata Baku (Indonesia)</label>
                        <input type="text" name="kata_baku" id="kata_baku" required placeholder="Contoh: tidak" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border py-2 px-3">
                    </div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Simpan Kata
                    </button>
                </div>
            </form>
        </div>

        <!-- Simulasi Normalisasi -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-fit">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                Simulasi Normalisasi
            </h3>
            <div class="space-y-4">
                <div>
                    <label for="input_simulasi" class="block text-sm font-medium text-slate-700">Masukkan Teks (Dialek Manado)</label>
                    <textarea id="input_simulasi" rows="3" placeholder="Ketik kata atau kalimat di sini..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border py-2 px-3"></textarea>
                </div>
                <button type="button" id="btn_uji" class="w-full flex justify-center py-2 px-4 border border-indigo-600 rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Uji Normalisasi
                </button>
                <div id="hasil_simulasi_container" class="hidden mt-4 p-4 bg-slate-50 border border-slate-200 rounded-lg">
                    <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Hasil Terjemahan (Baku)</p>
                    <p id="hasil_simulasi" class="text-sm text-gray-900 font-medium"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Leksikon -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-slate-50">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Kamus Leksikon</h3>
            <span class="text-sm text-slate-500">Total: {{ count($leksikons) }} Kata</span>
        </div>
        <div class="overflow-x-auto max-h-[600px]">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white sticky top-0">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kata Manado</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kata Baku</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($leksikons as $leksikon)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-indigo-600">
                                {{ $leksikon->kata_manado }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-slate-700">
                                {{ $leksikon->kata_baku }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('admin.leksikon.destroy', $leksikon->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kata ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded-md transition-colors">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-sm text-slate-500">
                                Kamus leksikon masih kosong.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnUji = document.getElementById('btn_uji');
        const inputSimulasi = document.getElementById('input_simulasi');
        const hasilContainer = document.getElementById('hasil_simulasi_container');
        const hasilSimulasi = document.getElementById('hasil_simulasi');

        btnUji.addEventListener('click', function() {
            let text = inputSimulasi.value.trim();
            if (!text) return;
            
            // Set loading state
            btnUji.disabled = true;
            btnUji.innerHTML = 'Memproses...';

            fetch('{{ route('admin.leksikon.simulasi') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ teks: text })
            })
            .then(response => response.json())
            .then(data => {
                hasilSimulasi.innerHTML = data.hasil;
                hasilContainer.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                hasilSimulasi.innerHTML = '<span class="text-red-500">Terjadi kesalahan pada server.</span>';
                hasilContainer.classList.remove('hidden');
            })
            .finally(() => {
                btnUji.disabled = false;
                btnUji.innerHTML = 'Uji Normalisasi';
            });
        });
    });
</script>
@endsection
