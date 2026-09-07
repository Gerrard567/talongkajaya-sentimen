@extends('layouts.admin')

@section('title', 'Pengaturan Akun Admin')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Info Panel -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-slate-900 px-6 py-10 flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-indigo-500 rounded-full flex items-center justify-center mb-4 shadow-lg border-4 border-slate-800 text-3xl font-bold text-white uppercase">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h3 class="text-xl font-bold text-white">{{ $user->name }}</h3>
                <p class="text-indigo-300 text-sm mt-1">Administrator Sistem CV Talongka Jaya</p>
                <div class="mt-6 inline-flex items-center px-3 py-1 rounded-full bg-green-500/20 text-green-300 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-green-400 mr-2"></span>
                    Akun Aktif
                </div>
            </div>
            <div class="px-6 py-6 bg-slate-50 border-t border-slate-100">
                <div class="flex flex-col space-y-4 text-sm text-slate-600">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-200">
                        <span class="font-medium text-slate-500">Email Utama</span>
                        <span class="font-semibold text-slate-800">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-slate-500">Tanggal Dibuat</span>
                        <span class="font-semibold text-slate-800">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Perbarui Informasi Akun
            </h3>
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md flex">
                    <svg class="h-5 w-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('admin.profil.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-5 mb-6">
                    <h4 class="text-sm font-bold text-indigo-900 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Verifikasi Keamanan
                    </h4>
                    <p class="text-xs text-indigo-700 mb-4">Untuk menyimpan perubahan profil atau mengganti kata sandi, harap konfirmasi identitas Anda dengan memasukkan password saat ini.</p>
                    
                    <div class="w-full md:w-1/2">
                        <label for="password_lama" class="block text-sm font-semibold text-slate-700">Password Saat Ini <span class="text-red-500">*</span></label>
                        <input type="password" name="password_lama" id="password_lama" required class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border transition-colors bg-white focus:bg-white">
                        @error('password_lama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border transition-colors bg-slate-50 focus:bg-white">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border transition-colors bg-slate-50 focus:bg-white">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-6 mt-6">
                    <h4 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wider">Ubah Kata Sandi</h4>
                    <p class="text-xs text-slate-500 mb-4">Kosongkan kolom di bawah ini jika tidak ingin mengubah kata sandi Anda saat ini.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700">Password Baru</label>
                            <input type="password" name="password" id="password" class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border transition-colors bg-slate-50 focus:bg-white">
                            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border transition-colors bg-slate-50 focus:bg-white">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="inline-flex justify-center items-center py-2.5 px-6 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:-translate-y-0.5">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
