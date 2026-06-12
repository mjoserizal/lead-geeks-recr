# LeadGeeks IT Support Portal

Sistem manajemen tiket IT Support internal yang dibangun dengan Laravel 13. Aplikasi ini memungkinkan tim IT untuk melacak, mengelola, dan menyelesaikan tiket support dengan efisien.

## 🌐 Live Demo

**Production URL:** https://lead-geeks.vercel.app

## 📋 Fitur

- **Dashboard** - Ringkasan statistik tiket (total, open, in progress, high priority)
- **Manajemen Tiket** - CRUD lengkap untuk tiket support
- **Komentar/Notes** - Tambahkan catatan pada tiket
- **Filter & Search** - Cari berdasarkan judul, assignee, atau deskripsi
- **Filter by Category** - Hardware, Software, Network, Security, Other
- **Filter by Priority** - Low, Medium, High
- **Filter by Status** - Open, In Progress, Resolved, Closed
- **Sorting** - Sort berdasarkan tanggal, judul, kategori, priority, status
- **Pagination** - 8 tiket per halaman

## 🛠 Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Framework | Laravel 13 |
| PHP Version | 8.3+ |
| Database | PostgreSQL (Neon) |
| Frontend | Blade Templates, Vanilla CSS |
| Icons | Lucide Icons |
| Fonts | Inter, Plus Jakarta Sans |
| Hosting | Vercel (Serverless PHP) |

## 📁 Struktur Project

```
lead-geeks-recr/
├── app/
│   ├── Http/Controllers/
│   │   ├── TicketController.php    # CRUD tiket + filtering
│   │   └── CommentController.php   # Komentar tiket
│   ├── Models/
│   │   ├── Ticket.php              # Model tiket
│   │   ├── Comment.php             # Model komentar
│   │   └── User.php
│   └── Providers/
│       └── AppServiceProvider.php  # Force HTTPS
├── api/
│   └── index.php                   # Entry point Vercel
├── bootstrap/
│   └── app.php                     # Bootstrap + Vercel config
├── config/
│   └── database.php                # Database connections
├── database/
│   ├── migrations/
│   │   ├── create_tickets_table.php
│   │   └── create_comments_table.php
│   └── seeders/
│       └── TicketSeeder.php
├── public/
│   └── css/
│       └── app.css                 # Stylesheet utama
├── resources/views/
│   ├── layouts/
│   │   └── layout.blade.php        # Layout utama
│   └── tickets/
│       ├── index.blade.php         # Dashboard + list tiket
│       ├── show.blade.php          # Detail tiket
│       └── edit.blade.php          # Edit tiket
├── routes/
│   └── web.php                     # Routes
├── vercel.json                     # Konfigurasi Vercel
└── .env.example                    # Environment template
```

## 🗄 Database Schema

### Tabel `tickets`

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| title | varchar(255) | Judul tiket |
| category | varchar(255) | Kategori (Hardware, Software, Network, Security, Other) |
| priority | enum | Low, Medium, High |
| status | enum | Open, In Progress, Resolved, Closed |
| assigned_to | varchar(255) | Nama assignee (nullable) |
| description | text | Deskripsi tiket (nullable) |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

### Tabel `comments`

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| ticket_id | bigint | Foreign key ke tickets |
| author | varchar(255) | Nama penulis komentar |
| comment | text | Isi komentar |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

## 🔗 API Routes

| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | `/` | TicketController@index | Dashboard |
| GET | `/tickets` | TicketController@index | List semua tiket |
| POST | `/tickets` | TicketController@store | Buat tiket baru |
| GET | `/tickets/{ticket}` | TicketController@show | Detail tiket |
| GET | `/tickets/{ticket}/edit` | TicketController@edit | Form edit tiket |
| PUT | `/tickets/{ticket}` | TicketController@update | Update tiket |
| DELETE | `/tickets/{ticket}` | TicketController@destroy | Hapus tiket |
| POST | `/tickets/{ticket}/comments` | CommentController@store | Tambah komentar |

### Query Parameters (GET /tickets)

| Parameter | Type | Description |
|-----------|------|-------------|
| search | string | Cari di title, assigned_to, description |
| category | string | Filter by kategori |
| priority | string | Filter by priority (Low, Medium, High) |
| status | string | Filter by status |
| sort_by | string | Sort column (created_at, title, category, priority, status) |
| sort_order | string | asc atau desc |

## 🚀 Deployment ke Vercel

### Prerequisites

- Node.js 18+
- PHP 8.3+
- Composer
- Akun Vercel
- Database PostgreSQL (Neon recommended)

### Setup Database (Neon)

1. Buat akun di https://neon.tech
2. Create new project
3. Copy connection string

### Deploy Steps

1. **Clone repository**
   ```bash
   git clone <repository-url>
   cd lead-geeks-recr
   ```

2. **Install dependencies**
   ```bash
   composer install --no-dev
   npm install
   npm run build
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure `.env` dengan database Neon**
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=<your-neon-host>
   DB_PORT=5432
   DB_DATABASE=neondb
   DB_USERNAME=<your-username>
   DB_PASSWORD=<your-password>
   DB_SSLMODE=require
   ```

5. **Run migrations**
   ```bash
   php artisan migrate --force
   ```

6. **Clear cache sebelum deploy**
   ```bash
   rm -rf bootstrap/cache/*.php
   php artisan package:discover
   ```

7. **Deploy ke Vercel**
   ```bash
   npm i -g vercel
   vercel login
   vercel --prod
   ```

### Vercel Environment Variables

Set di Vercel Dashboard → Settings → Environment Variables:

| Key | Value |
|-----|-------|
| APP_KEY | (hasil dari `php artisan key:generate --show`) |
| APP_ENV | production |
| APP_DEBUG | false |
| APP_URL | https://your-domain.vercel.app |
| DB_CONNECTION | pgsql |
| DB_HOST | your-neon-host |
| DB_PORT | 5432 |
| DB_DATABASE | neondb |
| DB_USERNAME | your-username |
| DB_PASSWORD | your-password |
| DB_SSLMODE | require |

## 💻 Local Development

1. **Clone dan install**
   ```bash
   git clone <repository-url>
   cd lead-geeks-recr
   composer install
   npm install
   ```

2. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Konfigurasi database** (edit `.env`)
   ```env
   DB_CONNECTION=sqlite
   ```

4. **Buat database SQLite**
   ```bash
   touch database/database.sqlite
   ```

5. **Run migrations dan seeder**
   ```bash
   php artisan migrate
   php artisan db:seed --class=TicketSeeder
   ```

6. **Start development server**
   ```bash
   composer run dev
   ```

7. Buka http://localhost:8000

## 📝 Catatan Penting

### Vercel Serverless Limitations

- **No persistent storage** - File system adalah read-only kecuali `/tmp`
- **Cold starts** - Function mungkin perlu waktu startup
- **Execution timeout** - Default 10 detik untuk Hobby plan
- **Database** - Harus pakai external database (tidak bisa SQLite)

### File Konfigurasi Khusus Vercel

- `api/index.php` - Entry point serverless function
- `vercel.json` - Routing dan environment config
- `bootstrap/app.php` - Setup `/tmp` storage untuk Vercel

## 🔧 Troubleshooting

### CSS tidak muncul
- Pastikan `APP_URL` di-set dengan HTTPS
- Check `AppServiceProvider` sudah ada `URL::forceScheme('https')`

### Database error
- Pastikan sudah run migration: `php artisan migrate --force`
- Check connection string di environment variables

### Class not found error
- Hapus cache: `rm -rf bootstrap/cache/*.php`
- Regenerate: `composer install --no-dev && php artisan package:discover`

## 📄 License

MIT License
