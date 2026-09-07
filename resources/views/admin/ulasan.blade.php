@extends('layouts.admin')

@section('title', 'Data Ulasan Pelanggan')
@section('content')
<div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <form action="{{ route('admin.ulasan') }}" method="GET" class="flex-1 flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-1/4">
                <label for="produk" class="block text-sm font-medium text-slate-700 mb-1">Filter Produk</label>
                <select name="produk" id="produk" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border py-2 px-3">
                    <option value="">Semua Produk</option>
                    <option value="Meja" {{ request('produk') == 'Meja' ? 'selected' : '' }}>Meja</option>
                    <option value="Kursi" {{ request('produk') == 'Kursi' ? 'selected' : '' }}>Kursi</option>
                    <option value="Lemari" {{ request('produk') == 'Lemari' ? 'selected' : '' }}>Lemari</option>
                    <option value="Dipan" {{ request('produk') == 'Dipan' ? 'selected' : '' }}>Dipan</option>
                </select>
            </div>
            <div class="w-full md:w-1/4">
                <label for="tanggal_awal" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ request('tanggal_awal') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border py-2 px-3">
            </div>
            <div class="w-full md:w-1/4">
                <label for="tanggal_akhir" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border py-2 px-3">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filter
                </button>
                @if(request()->hasAny(['produk', 'tanggal_awal', 'tanggal_akhir']))
                    <a href="{{ route('admin.ulasan') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-gray-50">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="flex items-center gap-2">
            <form action="{{ route('admin.ulasan.sinkronisasi') }}" method="POST" onsubmit="return confirm('Hitung ulang sentimen seluruh ulasan berdasarkan aturan leksikon terbaru?');">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center py-2 px-4 border border-indigo-200 rounded-md shadow-sm text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" title="Hitung ulang sentimen semua data jika ada aturan leksikon baru">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Sinkronkan Sentimen
                </button>
            </form>
            <a href="{{ route('admin.ulasan.export') }}" class="inline-flex items-center justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export CSV
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Teks Asli (Manado)</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Teks Normalisasi</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Umum</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Barang</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kirim</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pack</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu Masuk</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($ulasans as $index => $ulasan)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-slate-900">{{ $ulasan->nama_pelanggan }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-indigo-50 text-indigo-700">
                                {{ $ulasan->item_furniture }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 max-w-xs break-words italic">
                            "{{ $ulasan->teks_asli }}"
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 max-w-xs break-words">
                            {{ $ulasan->teks_normalisasi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($ulasan->label_sentimen == 'Positif')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-green-100 text-green-800">Positif</span>
                            @elseif ($ulasan->label_sentimen == 'Negatif')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-red-100 text-red-800">Negatif</span>
                            @else
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-slate-100 text-slate-600">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($ulasan->sentimen_barang == 'Positif')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-green-100 text-green-800">Positif</span>
                            @elseif ($ulasan->sentimen_barang == 'Negatif')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-red-100 text-red-800">Negatif</span>
                            @else
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-slate-100 text-slate-600">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($ulasan->sentimen_pengiriman == 'Positif')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-green-100 text-green-800">Positif</span>
                            @elseif ($ulasan->sentimen_pengiriman == 'Negatif')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-red-100 text-red-800">Negatif</span>
                            @else
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-slate-100 text-slate-600">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($ulasan->sentimen_packaging == 'Positif')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-green-100 text-green-800">Positif</span>
                            @elseif ($ulasan->sentimen_packaging == 'Negatif')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-red-100 text-red-800">Negatif</span>
                            @else
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-slate-100 text-slate-600">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $ulasan->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form action="{{ route('admin.ulasan.destroy', $ulasan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors font-medium">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="px-6 py-10 text-center text-sm text-slate-500">
                            Belum ada data ulasan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
