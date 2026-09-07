@extends('layouts.admin')

@section('title', 'Statistik Sentimen Ulasan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Card Total -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center mr-4">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Total Ulasan</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $total }}</h3>
        </div>
    </div>

    <!-- Card Positif -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center mr-4">
            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Sentimen Positif</p>
            <h3 class="text-2xl font-bold text-green-600">{{ $positif }}</h3>
        </div>
    </div>

    <!-- Card Negatif -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mr-4">
            <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Sentimen Negatif</p>
            <h3 class="text-2xl font-bold text-red-600">{{ $negatif }}</h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Tren Mingguan (Line) -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Ulasan 7 Hari Terakhir</h3>
        <canvas id="trendChart" height="100"></canvas>
    </div>

    <!-- Chart Persentase Sentimen -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 self-start">Persentase Sentimen</h3>
        <div class="w-full max-w-[220px]">
            <canvas id="sentimenChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Sentimen per Produk (Bar) -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Sentimen Berdasarkan Produk</h3>
        <canvas id="produkChart" height="100"></canvas>
    </div>

    <!-- Top 10 Words -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 overflow-hidden flex flex-col">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">10 Kata Dialek Terbanyak</h3>
        <div class="overflow-y-auto flex-1 max-h-[300px]">
            <ul class="divide-y divide-gray-200 pr-2">
                @forelse($topWords as $word => $count)
                    <li class="py-3 flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-900">{{ $word }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">{{ $count }}x</span>
                    </li>
                @empty
                    <li class="py-3 text-sm text-gray-500 text-center">Belum ada data teks.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 mb-8">
    <!-- Sentimen Berdasarkan Aspek (Bar) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Sentimen Berdasarkan Aspek Insight (Barang, Pengiriman, Packaging)</h3>
        <canvas id="aspekChart" height="80"></canvas>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-100 bg-slate-50">
        <h3 class="text-lg font-semibold text-gray-800">5 Ulasan Terbaru</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Teks Singkat</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Sentimen</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($ulasanTerbaru as $ulasan)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $ulasan->nama_pelanggan }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600 font-medium">{{ $ulasan->item_furniture }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600 truncate max-w-xs italic">"{{ \Illuminate\Support\Str::limit($ulasan->teks_asli, 40) }}"</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex flex-col items-end gap-1">
                            @if ($ulasan->sentimen_barang)
                                <div class="text-xs text-slate-500">Barang: 
                                    <span class="px-2 py-0.5 inline-flex leading-tight font-semibold rounded bg-{{ $ulasan->sentimen_barang == 'Positif' ? 'green-100 text-green-800' : 'red-100 text-red-800' }}">{{ $ulasan->sentimen_barang }}</span>
                                </div>
                            @endif
                            @if ($ulasan->sentimen_pengiriman)
                                <div class="text-xs text-slate-500">Kirim: 
                                    <span class="px-2 py-0.5 inline-flex leading-tight font-semibold rounded bg-{{ $ulasan->sentimen_pengiriman == 'Positif' ? 'green-100 text-green-800' : 'red-100 text-red-800' }}">{{ $ulasan->sentimen_pengiriman }}</span>
                                </div>
                            @endif
                            @if ($ulasan->sentimen_packaging)
                                <div class="text-xs text-slate-500">Pack: 
                                    <span class="px-2 py-0.5 inline-flex leading-tight font-semibold rounded bg-{{ $ulasan->sentimen_packaging == 'Positif' ? 'green-100 text-green-800' : 'red-100 text-red-800' }}">{{ $ulasan->sentimen_packaging }}</span>
                                </div>
                            @endif
                            @if (!$ulasan->sentimen_barang && !$ulasan->sentimen_pengiriman && !$ulasan->sentimen_packaging)
                                <span class="text-xs text-slate-400">Umum: 
                                    <span class="px-2 py-0.5 inline-flex leading-tight font-semibold rounded bg-{{ $ulasan->label_sentimen == 'Positif' ? 'green-100 text-green-800' : ($ulasan->label_sentimen == 'Negatif' ? 'red-100 text-red-800' : 'slate-100 text-slate-600') }}">{{ $ulasan->label_sentimen ?? '-' }}</span>
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada ulasan terbaru.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Chart Persentase Sentimen (Donut)
    const ctxDonut = document.getElementById('sentimenChart').getContext('2d');
    let donutData = [{{ $positif }}, {{ $negatif }}];
    let donutColors = ['#10B981', '#EF4444'];
    let donutLabels = ['Positif', 'Negatif'];
    
    if({{ $total }} === 0) {
        donutData = [1];
        donutColors = ['#E5E7EB'];
        donutLabels = ['Belum Ada Data'];
    }

    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: donutLabels,
            datasets: [{
                data: donutData,
                backgroundColor: donutColors,
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // 2. Grafik Tren Mingguan (Line)
    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: {!! json_encode($trendDates) !!},
            datasets: [
                {
                    label: 'Positif',
                    data: {!! json_encode($trendPositif) !!},
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Negatif',
                    data: {!! json_encode($trendNegatif) !!},
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });

    // 3. Grafik Sentimen per Produk (Bar)
    const ctxProduk = document.getElementById('produkChart').getContext('2d');
    new Chart(ctxProduk, {
        type: 'bar',
        data: {
            labels: {!! json_encode($products) !!},
            datasets: [
                {
                    label: 'Positif',
                    data: {!! json_encode($productSentimen['positif']) !!},
                    backgroundColor: '#10B981',
                    borderRadius: 4
                },
                {
                    label: 'Negatif',
                    data: {!! json_encode($productSentimen['negatif']) !!},
                    backgroundColor: '#EF4444',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });

    // 4. Grafik Sentimen per Aspek
    const ctxAspek = document.getElementById('aspekChart').getContext('2d');
    new Chart(ctxAspek, {
        type: 'bar',
        data: {
            labels: ['Kecacatan/Barang', 'Pengiriman', 'Packaging'],
            datasets: [
                {
                    label: 'Positif',
                    data: [{{ $aspekBarang['positif'] }}, {{ $aspekPengiriman['positif'] }}, {{ $aspekPackaging['positif'] }}],
                    backgroundColor: '#10B981',
                    borderRadius: 4
                },
                {
                    label: 'Negatif',
                    data: [{{ $aspekBarang['negatif'] }}, {{ $aspekPengiriman['negatif'] }}, {{ $aspekPackaging['negatif'] }}],
                    backgroundColor: '#EF4444',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });
</script>
@endsection
