<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kritik & Saran Pelanggan - CV Talongka Jaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 antialiased min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden relative z-10">
        
        <!-- Header Box -->
        <div class="bg-slate-900 px-6 py-8 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-slate-800 rounded-full opacity-50"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-16 h-16 bg-slate-800 rounded-full opacity-50"></div>
            
            <div class="relative z-10 flex flex-col items-center justify-center">
                <div class="w-14 h-14 bg-yellow-500 rounded-full flex items-center justify-center mb-4 shadow-lg border-4 border-slate-900">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white tracking-tight">Kritik & Saran Pelanggan</h2>
                <p class="text-slate-300 mt-1 text-sm">CV Talongka Jaya</p>
            </div>
        </div>

        <!-- Form Content -->
        <div class="px-8 py-8">
            <form action="{{ route('ulasan.public.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="nama_pelanggan" class="block text-sm font-semibold text-slate-700">Nama Pelanggan</label>
                    <div class="mt-2">
                        <input id="nama_pelanggan" name="nama_pelanggan" type="text" required class="block w-full rounded-lg border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-yellow-500 sm:text-sm sm:leading-6 transition-all duration-200 bg-slate-50 focus:bg-white" placeholder="Masukkan nama lengkap Anda">
                    </div>
                    @error('nama_pelanggan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="item_furniture" class="block text-sm font-semibold text-slate-700">Produk Furniture</label>
                    <div class="mt-2 relative">
                        <select id="item_furniture" name="item_furniture" required class="block w-full rounded-lg border-0 py-3 pl-4 pr-10 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-yellow-500 sm:text-sm sm:leading-6 appearance-none transition-all duration-200 bg-slate-50 focus:bg-white">
                            <option value="" disabled selected>Pilih produk yang dibeli...</option>
                            <option value="Meja">Meja</option>
                            <option value="Kursi">Kursi</option>
                            <option value="Lemari">Lemari</option>
                            <option value="Dipan">Dipan</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('item_furniture')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="teks_asli" class="block text-sm font-semibold text-slate-700">Ulasan (Dialek Manado)</label>
                    <div class="mt-2">
                        <textarea id="teks_asli" name="teks_asli" rows="4" required class="block w-full rounded-lg border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-yellow-500 sm:text-sm sm:leading-6 transition-all duration-200 bg-slate-50 focus:bg-white" placeholder="wow sangat bagus itu kualitas kursi pe kayu"></textarea>
                    </div>
                    @error('teks_asli')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 rounded-xl shadow-md text-sm font-bold text-slate-900 bg-yellow-500 hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition duration-200 ease-in-out transform hover:-translate-y-0.5">
                        Kirim Ulasan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Modal using Alpine.js -->
    @if(session('public_success'))
    <div x-data="{ open: true }" 
         x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true"
         style="display: none;">
         
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" 
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                
                <div>
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                        <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-xl leading-6 font-bold text-slate-900" id="modal-title">
                            Terima Kasih!
                        </h3>
                        <div class="mt-3">
                            <p class="text-sm text-slate-600">
                                Ulasan Anda telah berhasil dikirim. Masukan Anda sangat berarti bagi peningkatan kualitas produk CV Talongka Jaya.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 sm:mt-8">
                    <button @click="open = false" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-3 bg-slate-900 text-base font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 sm:text-sm transition-colors duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Floating Admin Login Icon -->
    <div class="fixed bottom-4 right-4 z-50">
        <a href="{{ route('login') }}" class="flex items-center justify-center w-10 h-10 bg-slate-900 text-slate-400 hover:text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 group border border-slate-700 hover:border-yellow-500" title="Admin Login">
            <svg class="w-5 h-5 group-hover:rotate-45 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </a>
    </div>

</body>
</html>
