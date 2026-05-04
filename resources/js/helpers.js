/**
 * HELPERS.JS
 * Kumpulan fungsi JavaScript yang akan kita pakai di seluruh project.
 * Kenza bisa belajar dari komentar di setiap fungsi!
 */

// ============================================================
// 1. FORMAT RUPIAH
// Mengubah angka menjadi format mata uang Rupiah
// Contoh: formatRupiah(15000) → "Rp 15.000"
// ============================================================
export function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(angka);
}

// ============================================================
// 2. PREVIEW GAMBAR
// Membaca file gambar dari input dan menampilkan preview-nya
// Menggunakan FileReader API — API bawaan browser untuk baca file
// ============================================================
export function previewGambar(inputElement, previewElement) {
    const file = inputElement.files[0]; // Ambil file pertama yang dipilih

    // Validasi: pastikan file ada dan bertipe gambar
    if (!file) return;
    if (!file.type.startsWith('image/')) {
        alert('File harus berupa gambar!');
        return;
    }

    // FileReader membaca file secara async (tidak memblokir halaman)
    const reader = new FileReader();

    // Event: dipanggil ketika pembacaan selesai
    reader.onload = function(event) {
        previewElement.src = event.target.result; // result = base64 string gambar
        previewElement.classList.remove('hidden');
    };

    reader.readAsDataURL(file); // Mulai baca file sebagai Data URL (base64)
}

// ============================================================
// 3. KONFIRMASI HAPUS
// Menampilkan dialog konfirmasi sebelum menghapus data
// Mengembalikan true jika user klik OK, false jika batal
// ============================================================
export function konfirmasiHapus(nama = 'data ini') {
    return confirm(`Apakah kamu yakin ingin menghapus ${nama}? Tindakan ini tidak dapat dibatalkan.`);
}

// ============================================================
// 4. TOAST NOTIFICATION
// Menampilkan notifikasi kecil di pojok layar
// type: 'success' | 'error' | 'warning' | 'info'
// ============================================================
export function showToast(pesan, type = 'success', durasi = 3000) {
    // Buat elemen toast
    const toast = document.createElement('div');

    // Tentukan warna berdasarkan type
    const warna = {
        success: 'bg-green-500',
        error:   'bg-red-500',
        warning: 'bg-yellow-500',
        info:    'bg-blue-500',
    }[type] || 'bg-green-500';

    // Set class dan isi toast
    toast.className = `fixed bottom-4 right-4 z-50 flex items-center gap-2 px-4 py-3 rounded-xl text-white text-sm font-medium shadow-lg transform translate-y-10 opacity-0 transition-all duration-300 ${warna}`;
    toast.innerHTML = pesan;

    // Tambahkan ke body
    document.body.appendChild(toast);

    // Animasi masuk (setelah 10ms agar transition berjalan)
    setTimeout(() => {
        toast.classList.remove('translate-y-10', 'opacity-0');
    }, 10);

    // Animasi keluar setelah durasi
    setTimeout(() => {
        toast.classList.add('translate-y-10', 'opacity-0');
        // Hapus elemen setelah animasi selesai
        setTimeout(() => toast.remove(), 300);
    }, durasi);
}

// ============================================================
// 5. DEBOUNCE
// Menunda eksekusi fungsi sampai user berhenti mengetik
// Berguna untuk search live agar tidak request setiap ketikan
// Contoh: debounce(cariProduk, 500) → tunggu 500ms setelah user berhenti ketik
// ============================================================
export function debounce(fungsi, delay = 300) {
    let timer; // Simpan referensi timer

    return function(...args) {
        clearTimeout(timer); // Reset timer setiap kali fungsi dipanggil
        timer = setTimeout(() => {
            fungsi.apply(this, args); // Panggil fungsi asli setelah delay
        }, delay);
    };
}

// ============================================================
// 6. FORMAT TANGGAL INDONESIA
// Mengubah tanggal menjadi format Indonesia
// Contoh: formatTanggal('2024-01-15') → "15 Januari 2024"
// ============================================================
export function formatTanggal(tanggal) {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(new Date(tanggal));
}

// ============================================================
// 7. VALIDASI FORM
// Memvalidasi input form sebelum submit
// Mengembalikan object { valid: bool, errors: {} }
// ============================================================
export function validasiForm(rules) {
    const errors = {};

    for (const [field, config] of Object.entries(rules)) {
        const nilai = config.value?.trim();

        // Validasi required
        if (config.required && !nilai) {
            errors[field] = config.label + ' wajib diisi.';
            continue;
        }

        // Validasi min length
        if (config.minLength && nilai.length < config.minLength) {
            errors[field] = config.label + ` minimal ${config.minLength} karakter.`;
            continue;
        }

        // Validasi angka positif
        if (config.positiveNumber && (isNaN(nilai) || Number(nilai) <= 0)) {
            errors[field] = config.label + ' harus berupa angka positif.';
        }
    }

    return {
        valid: Object.keys(errors).length === 0,
        errors,
    };
}