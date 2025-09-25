# To-Do List App

Aplikasi to-do list sederhana yang dibangun menggunakan PHP native dengan CSS Tailwind. Aplikasi ini dirancang untuk bekerja secara responsif dan fungsional di perangkat mobile.

## Fitur Utama

### ✅ Fungsionalitas To-Do List
1. **Menulis dan menambahkan agenda** - Pengguna dapat menambahkan tugas baru melalui form
2. **Menampilkan daftar to-do** - Tampilan tugas yang harus dikerjakan hari ini
3. **Mencoret pekerjaan selesai** - Mengklik checkbox untuk menandai tugas sebagai selesai
4. **Menampilkan pekerjaan selesai** - Kolom DONE menampilkan semua tugas yang telah diselesaikan
5. **Menghapus seluruh to-do list** - Button untuk menghapus semua tugas sekaligus

### 🎯 Komponen Aplikasi
- **Profile**: Menampilkan informasi nama dan jabatan pengguna
- **Time**: Menampilkan informasi waktu berupa hari dan tanggal (update otomatis)
- **Text Area**: Area untuk menuliskan deskripsi tugas
- **Level Prioritas**: 3 tingkat prioritas (Low, Medium, High) dengan warna berbeda
- **Button Submit**: Tombol untuk menambahkan tugas baru
- **Kolom Centang**: Checkbox untuk mengecek status To Do
- **To Do**: Tabel berisi tugas yang akan/sedang dikerjakan
- **Done**: List tugas yang sudah diselesaikan
- **Button Delete All**: Menghapus seluruh list to do
- **Button Delete**: Menghapus satu tugas tertentu

## Teknologi yang Digunakan

- **Backend**: PHP 7.4+ dengan PDO untuk database
- **Database**: MySQL dengan auto-creation database dan tabel
- **Frontend**: HTML5, CSS3, Tailwind CSS
- **Icons**: Font Awesome 6
- **Responsive Design**: Mobile-first approach

## Instalasi dan Setup

### Prasyarat
- PHP 7.4 atau lebih baru
- MySQL/MariaDB
- Web server (Apache/Nginx) atau PHP built-in server

### Langkah Instalasi

1. **Clone atau download project**
   ```bash
   git clone [repository-url]
   cd harisenin_perencanaan
   ```

2. **Konfigurasi Database**
   - Buka file `index.php`
   - Edit konfigurasi database di bagian atas file:
   ```php
   $host = 'localhost';
   $dbname = 'todo_app';
   $username = 'root';
   $password = 'your_password';
   ```

3. **Jalankan Aplikasi**
   
   **Opsi 1: Menggunakan PHP Built-in Server**
   ```bash
   php -S localhost:8000
   ```
   
   **Opsi 2: Menggunakan Web Server**
   - Copy file ke direktori web server (htdocs/www)
   - Akses melalui browser

4. **Akses Aplikasi**
   - Buka browser dan akses `http://localhost:8000`
   - Database dan tabel akan dibuat otomatis saat pertama kali diakses

## Struktur Database

### Tabel `todos`
```sql
CREATE TABLE todos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task TEXT NOT NULL,
    priority ENUM('Low', 'Medium', 'High') DEFAULT 'Medium',
    is_completed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL
);
```

## Fitur Responsif

- **Mobile-First Design**: Optimized untuk layar kecil
- **Flexible Grid**: Layout menyesuaikan ukuran layar
- **Touch-Friendly**: Button dan checkbox mudah di-tap
- **Readable Typography**: Font size optimal untuk mobile
- **Efficient Space Usage**: Konten terorganisir dengan baik

## Cara Penggunaan

1. **Menambah Tugas**
   - Isi deskripsi tugas di text area
   - Pilih level prioritas (Low/Medium/High)
   - Klik "Add Task"

2. **Menyelesaikan Tugas**
   - Klik checkbox di sebelah kiri tugas
   - Tugas akan pindah ke kolom "Done"

3. **Menghapus Tugas**
   - Klik ikon trash untuk menghapus satu tugas
   - Klik "Delete All Tasks" untuk menghapus semua

4. **Melihat Status**
   - Kolom "To Do": Tugas yang belum selesai
   - Kolom "Done": Tugas yang sudah selesai
   - Prioritas ditampilkan dengan warna berbeda

## Customization

### Mengubah Informasi User
Edit bagian session di `index.php`:
```php
$_SESSION['user'] = [
    'name' => 'Your Name',
    'position' => 'Your Position'
];
```

### Mengubah Warna Prioritas
Edit CSS classes di bagian `<style>`:
```css
.priority-low { @apply bg-green-100 text-green-800 border-green-200; }
.priority-medium { @apply bg-yellow-100 text-yellow-800 border-yellow-200; }
.priority-high { @apply bg-red-100 text-red-800 border-red-200; }
```

## Browser Support

- Chrome 60+
- Firefox 60+
- Safari 12+
- Edge 79+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Security Features

- Input sanitization dengan `htmlspecialchars()`
- Prepared statements untuk mencegah SQL injection
- Session management untuk user data
- Form validation

## Performance

- Lightweight design (< 100KB total)
- Optimized database queries
- Minimal external dependencies
- Fast loading pada koneksi lambat

## Troubleshooting

### Database Connection Error
- Pastikan MySQL server berjalan
- Cek username/password database
- Pastikan database server accessible

### CSS Tidak Load
- Pastikan koneksi internet untuk CDN Tailwind
- Cek console browser untuk error

### Mobile Layout Issues
- Pastikan viewport meta tag ada
- Test di berbagai ukuran layar
- Clear browser cache

## License

MIT License - Feel free to use and modify as needed.

## Support

Untuk pertanyaan atau bantuan, silakan buat issue di repository ini.
