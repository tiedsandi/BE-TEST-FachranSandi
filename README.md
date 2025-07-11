# 📦 E-Procurement API

## 🛠️ Teknologi

-   Laravel 12
-   MySQL
-   JWT Auth (`tymon/jwt-auth`)

## 🚀 Instalasi & Setup

### 1. Clone repo

```bash
git clone https://github.com/tiedsandi/vhiweb-be-test
cd vhiweb-be-test
```

### 2. Install dependencies

```bash
composer install
```

### 3. Copy file `.env`

```bash
cp .env.example .env
```

Atur konfigurasi `.env`, khususnya:

-   `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
-   `JWT_SECRET` (`php artisan jwt:secret` jika ingin regenerate)

### 4. Generate key & migrate database

```bash
php artisan key:generate
php artisan migrate
```

### 5. Jalankan server

```bash
php artisan serve
```

---

## 🔐 Autentikasi

Gunakan JWT Token di header:

```
Authorization: Bearer {token}
```

> Semua endpoint (kecuali register & login) butuh header:
> `Authorization: Bearer {token}`

### 🔸 Register

`POST /api/register`

### 🔸 Login

`POST /api/login`

---

## 🏢 Vendor

### 🔸 Register Vendor

`POST /api/vendors`

---

## 📦 Produk

### 🔸 Lihat Daftar Produk

`GET /api/products`

### 🔸 Lihat Detail Produk

`GET /api/products/{id}`

### 🔸 Tambah Produk

`POST /api/products`

### 🔸 Update Produk

`PUT /api/products/{id}`

### 🔸 Hapus Produk

`DELETE /api/products/{id}`

## 💡 Catatan

-   Produk hanya bisa diakses jika vendor-nya milik user yang login.
-   Response dibungkus dengan format JSON standar (`success`, `message`, `data`).

---

## 📬 Contoh Response

### ✅ Login

```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "token": "<jwt-token>",
        "user": {
            "name": "Sandi QA",
            "email": "sandi.qa@example.com",
            "created_at": "2025-07-10 09:43:40"
        }
    }
}
```

### ✅ Register Vendor

```json
{
    "success": true,
    "message": "Vendor registered successfully",
    "data": {
        "vendor": {
            "vendor_name": "Perusahaan F",
            "created_by": 3,
            "created_by_name": "Sandi QA",
            "created_at": "2025-07-11 03:22:15"
        }
    }
}
```
