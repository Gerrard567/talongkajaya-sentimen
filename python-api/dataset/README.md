# Directory Dataset 1800 Ulasan

Silakan letakkan file dataset 1.800 ulasan Anda di dalam folder ini dengan nama:
- `dataset_1800.csv` (atau `dataset.csv`)

### Format Kolom yang Didukung:
1. Kolom Teks: `teks_asli` atau `teks` atau `ulasan` atau `komentar`
2. Kolom Label Sentimen: `label_sentimen` atau `label` atau `sentimen` (Isi: `Positif` / `Negatif` atau `1` / `0`)

Skrip Re-Training (`app/Python/train.py`) akan secara otomatis membaca file ini dan menggabungkannya dengan ulasan-ulasan baru yang ada di database MySQL!
