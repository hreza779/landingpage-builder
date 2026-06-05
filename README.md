<div align="center">
  <h1>🚀 Homplus Landing Manager</h1>
  <p><strong>A Lightweight, Portable, and Smart Landing Page Manager & Tracker</strong></p>

  <p>
    <a href="#features">Features</a> •
    <a href="#installation">Installation</a> •
    <a href="#how-it-works">How it Works</a> •
    <a href="#راهنمای-فارسی">فارسی</a>
  </p>
</div>

---

**Homplus Landing Manager** is a complete, flat-file (SQLite) solution for creating, managing, and tracking landing pages. It requires **zero configuration**, no MySQL database setup, and works instantly on any standard PHP hosting. 

It comes with a built-in code editor, an automatic UTM tracker, a smart lead capture system (Forms), and a file manager—all wrapped in a beautiful, bilingual (English & Persian) dashboard!

## ✨ Features

- 🔋 **Zero Config & Portable**: Drop the files on your server and it works! Uses SQLite for a lightweight, flat-file database architecture.
- 🌍 **Bilingual Dashboard**: Fully supports **English** (LTR) and **Persian** (RTL) with dynamic language switching.
- 📝 **Live Code Editor**: Create and edit landing pages (HTML/CSS/JS) directly from the dashboard. Includes a live responsive preview (Desktop, Tablet, Mobile).
- 📊 **Advanced Analytics**: Automatically tracks total visits, today's visits, Device OS, Referrers, and **UTM Parameters** (Source, Campaign, Medium).
- 🎯 **Smart Lead Capture**: Automatically intercepts ANY `<form>` in your landing pages. No backend code required on your part! It extracts names, normalizes phone numbers (converts Persian/Arabic digits automatically), and stores form data.
- 📥 **CSV Export**: Export all your leads and form submissions (including their UTM source) to an Excel-ready CSV file.
- 📁 **File Manager**: Upload, delete, and copy direct links for images, videos, and PDFs to use in your landing pages.
- 🔒 **Secure**: Password-protected dashboard, anti-XSS sanitized inputs, and `.htaccess` protected database directories.

## 🛠 Requirements

- PHP 7.4 or higher (PHP 8.x recommended)
- PDO SQLite Extension enabled (Standard on 99% of web hosts)

## 🚀 Installation

1. Clone or download this repository.
2. Upload the files to your server (e.g., `public_html/landings/`).
3. Open the `stats.php` URL in your browser: `https://yourwebsite.com/landings/stats.php`
4. Log in using the default password: **`admin`**
5. **Important:** Open `stats.php` in a text editor and change the `$admin_password` variable on line `188` to a secure password!

## 💡 How it Works

1. **Create a Landing**: In the dashboard, click on "New Landing". Give it a name.
2. **Edit the Code**: You will be taken to the built-in editor. Paste your HTML design. 
3. **Automatic Tracking**: The system automatically injects a tiny tracking script into your new landing pages. It handles visit logs and intercepts all `<form>` tags.
4. **Publish & Track**: Share your landing page link (e.g., `yoursite.com/landings/promo.html?utm_source=instagram`). Watch the visits and form submissions populate live in the `stats.php` dashboard!

---

<div align="right" dir="rtl" id="راهنمای-فارسی">

# 🇮🇷 راهنمای فارسی (Homplus Landing Manager)

این پروژه یک سیستم مدیریت لندینگ پیج (صفحات فرود) بسیار سبک و هوشمند است که بدون نیاز به هیچ‌گونه نصب دیتابیس (MySQL) کار می‌کند. فقط کافیست فایل‌ها را روی هاست خود آپلود کنید!

### 🌟 امکانات اصلی:
- **پرتابل و بدون دیتابیس:** استفاده از SQLite (کارکرد سریع روی تمامی هاست‌های PHP).
- **داشبورد دو زبانه:** پشتیبانی کامل از فارسی (راست‌چین با فونت وزیرمتن) و انگلیسی.
- **ویرایشگر زنده:** دارای ادیتور کدهای HTML با قابلیت پیش‌نمایش در سایز موبایل، تبلت و دسکتاپ.
- **ردیاب هوشمند (Tracker):** ثبت خودکار بازدیدها، آی‌پی، نوع دستگاه و تگ‌های UTM (مخصوص کمپین‌های تبلیغاتی).
- **سیستم فرم‌ساز جادویی:** هر فرمی (تگ `<form>`) که در لندینگ خود بسازید، سیستم به صورت خودکار اطلاعات آن را دریافت، شماره موبایل‌ها را نرمال‌سازی (تبدیل اعداد فارسی به انگلیسی) و در دیتابیس ذخیره می‌کند.
- **خروجی اکسل:** دانلود تمامی لیدها و درخواست‌ها با یک کلیک.
- **فایل منیجر:** مدیریت و آپلود عکس و ویدیو برای استفاده در صفحات فرود.

### 🚀 آموزش نصب:
1. فایل‌های پروژه را روی هاست آپلود کنید.
2. آدرس `stats.php` را در مرورگر باز کنید (مثال: `site.com/stats.php`).
3. با رمز عبور پیش‌فرض `admin` وارد سیستم شوید.
4. **نکته امنیتی:** حتماً فایل `stats.php` را باز کنید و در حدود خط ۱۸۸، مقدار متغیر `$admin_password` را به رمز دلخواه خود تغییر دهید.

</div>

---
*Created with ❤️ by the Homplus Team.*
