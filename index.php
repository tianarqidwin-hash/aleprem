<?php
// Page View Counter
include 'counter.php';

// Data Produk Aplikasi dengan URL Logo Official/Asli
$products = [
    'netflix' => [
        'name' => 'Netflix Premium',
        'price' => 45000,
        'duration' => '1 Bulan',
        'logo' => 'https://pngimg.com/uploads/netflix/netflix_PNG10.png',
        'desc' => 'Akses tayangan film dan serial favorit tanpa batas dengan kualitas video 4K Ultra HD.',
        'features' => [
            'Kualitas Video 4K Ultra HD',
            'Private Profile + Kunci PIN Sendiri',
            'Bisa Digunakan di Smart TV, HP, & Laptop',
            'Garansi Penuh Selama Masa Aktif'
        ]
    ],
    'canva' => [
        'name' => 'Canva Pro',
        'price' => 20000,
        'duration' => '1 Tahun',
        'logo' => 'https://freelogopng.com/images/all_img/1656733637logo-canva-png.png',
        'desc' => 'Akses seluruh aset desain, font premium, Brand Kit, dan fitur AI tanpa batas.',
        'features' => [
            'Unlock All Premium Templates & Assets',
            'Fitur Brand Kit & Background Remover',
            'Undangan Langsung ke Email Pribadi',
            'Penyimpanan Cloud Desain 100GB'
        ]
    ],
    'capcut' => [
        'name' => 'CapCut Pro',
        'price' => 25000,
        'duration' => '1 Bulan',
        'logo' => 'https://1000logos.net/wp-content/uploads/2025/01/CapCut-Emblem-2048x1152.png',
        'desc' => 'Edit video profesional tanpa watermark dengan efek, transisi, dan filter pro unlocked.',
        'features' => [
            'Semua Efek & Transisi Pro Unlocked',
            'Export Tanpa Watermark hingga Kualitas 4K',
            'Penyimpanan Cloud 100GB',
            'Dapat Digunakan di PC & Smartphone'
        ]
    ],
    'spotify' => [
        'name' => 'Spotify Premium',
        'price' => 30000,
        'duration' => '1 Bulan',
        'logo' => 'https://upload.wikimedia.org/wikipedia/commons/1/19/Spotify_logo_without_text.svg',
        'desc' => 'Dengarkan jutaan lagu bebas iklan dengan kualitas audio paling jernih dan fitur mode offline.',
        'features' => [
            'Mendengarkan Musik Tanpa Iklan',
            'Download & Mode Offline',
            'Kualitas Suara Sangat Tinggi (High Quality)',
            'Gunakan di Akun Pribadi Kamu'
        ]
    ]
];

// Routing Halaman
$page = $_GET['page'] ?? 'products';
$productId = $_GET['id'] ?? 'netflix';

// Validasi ID Produk
if (!array_key_exists($productId, $products)) {
    $productId = 'netflix';
}
$selectedProduct = $products[$productId];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ale Mium - Premium App Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- NAVIGASI UTAMA -->
    <header>
        <div class="logo">
            <h2><a href="index.php?page=products"><i class="fa-solid fa-bolt"></i> Ale Mium</a></h2>
        </div>
        <nav>
            <ul>
                <li><a href="index.php?page=home" class="<?php echo ($page == 'home') ? 'active' : ''; ?>">Home</a></li>
                <li><a href="index.php?page=products" class="<?php echo ($page == 'products') ? 'active' : ''; ?>">Products</a></li>
                <li><a href="index.php?page=order&id=<?php echo $productId; ?>" class="<?php echo ($page == 'order') ? 'active' : ''; ?>">Keranjang / Order</a></li>
                <li><a href="index.php?page=contact" class="<?php echo ($page == 'contact') ? 'active' : ''; ?>">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">

        <!-- 1. HALAMAN HOME -->
        <?php if ($page == 'home'): ?>
            <section>
                <div class="info-card">
                    <h1 style="font-size: 2rem; margin-bottom: 10px;">Selamat Datang di Ale Mium</h1>
                    <p style="color: var(--text-sub); margin-bottom: 20px;">Pusat aplikasi premium resmi, murah, dan terpercaya.</p>
                    <a href="index.php?page=products" class="btn-buy" style="display:inline-flex; width:auto; padding:12px 28px;">
                        <i class="fa-solid fa-bag-shopping"></i> Lihat Katalog Produk
                    </a>
                </div>
            </section>

        <!-- 2. HALAMAN KATALOG PRODUK -->
        <?php elseif ($page == 'products'): ?>
            <section>
                <h2 class="section-title"><i class="fa-solid fa-layer-group"></i> Katalog Aplikasi Premium</h2>
                
                <div class="product-grid">
                    <?php foreach ($products as $id => $item): ?>
                        <div class="product-card">
                            <div>
                                <!-- Logo Asli Produk -->
                                <div class="card-icon">
                                    <img src="<?php echo $item['logo']; ?>" alt="<?php echo $item['name']; ?>" class="brand-logo">
                                </div>
                                <h3 class="product-title"><?php echo $item['name']; ?></h3>
                                <span class="product-badge">Durasi: <?php echo $item['duration']; ?></span>
                                <p style="font-size:0.85rem; color:var(--text-sub); margin-bottom:15px;"><?php echo $item['desc']; ?></p>
                            </div>
                            <div>
                                <div class="product-price">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></div>
                                <div class="card-actions">
                                    <a href="index.php?page=detail&id=<?php echo $id; ?>" class="btn-detail">Detail</a>
                                    <a href="index.php?page=order&id=<?php echo $id; ?>" class="btn-buy">
                                        <i class="fa-solid fa-cart-plus"></i> Beli
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

        <!-- 3. HALAMAN DETAIL SATU PRODUK -->
        <?php elseif ($page == 'detail'): ?>
            <section>
                <div class="detail-card">
                    <div class="detail-header">
                        <div class="detail-icon">
                            <img src="<?php echo $selectedProduct['logo']; ?>" alt="<?php echo $selectedProduct['name']; ?>" class="brand-logo">
                        </div>
                        <div>
                            <h2 style="font-size: 1.8rem;"><?php echo $selectedProduct['name']; ?></h2>
                            <span style="color: var(--text-sub);">Masa Aktif: <?php echo $selectedProduct['duration']; ?></span>
                            <div style="font-size: 1.6rem; font-weight: 800; color: var(--accent); margin-top: 5px;">
                                Rp <?php echo number_format($selectedProduct['price'], 0, ',', '.'); ?>
                            </div>
                        </div>
                    </div>

                    <p style="color: var(--text-sub); line-height: 1.7;"><?php echo $selectedProduct['desc']; ?></p>

                    <h4 style="margin-top: 25px; color: #fff;">Fitur & Keunggulan Layanan:</h4>
                    <ul class="detail-features">
                        <?php foreach ($selectedProduct['features'] as $ft): ?>
                            <li><i class="fa-solid fa-circle-check"></i> <?php echo $ft; ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <div style="display: flex; gap: 15px; margin-top: 30px;">
                        <a href="index.php?page=products" class="btn-detail" style="padding: 14px;">&laquo; Kembali ke Katalog</a>
                        <a href="index.php?page=order&id=<?php echo $productId; ?>" class="btn-buy" style="padding: 14px; font-size: 1rem;">
                            <i class="fa-solid fa-credit-card"></i> Lanjut Pembayaran
                        </a>
                    </div>
                </div>
            </section>

        <!-- 4. HALAMAN KERANJANG / ORDER -->
        <?php elseif ($page == 'order'): ?>
            <section>
                <h2 class="section-title"><i class="fa-solid fa-cart-shopping"></i> Keranjang Pembelian</h2>
                <div class="form-card">
                    
                    <!-- Box Produk Terpilih dengan Logo Asli -->
                    <div class="cart-item-box">
                        <div class="cart-item-info">
                            <div class="card-icon" style="margin-bottom: 0;">
                                <img src="<?php echo $selectedProduct['logo']; ?>" alt="<?php echo $selectedProduct['name']; ?>" class="brand-logo">
                            </div>
                            <div>
                                <h3 style="font-size: 1.1rem;"><?php echo $selectedProduct['name']; ?></h3>
                                <span style="font-size: 0.85rem; color: var(--text-sub);">Durasi: <?php echo $selectedProduct['duration']; ?></span>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 1.2rem; font-weight: 800; color: var(--accent);">
                                Rp <?php echo number_format($selectedProduct['price'], 0, ',', '.'); ?>
                            </div>
                            <a href="index.php?page=products" style="font-size: 0.8rem; color: var(--primary); text-decoration: none;">Ganti Produk</a>
                        </div>
                    </div>

<form action="proses.php" method="post" enctype="multipart/form-data">
                        
                        <input type="hidden" name="package" value="<?php echo $selectedProduct['name']; ?>">
                        <input type="hidden" id="basePrice" value="<?php echo $selectedProduct['price']; ?>">

                        <div class="form-group">
                            <label>Nama Lengkap:</label>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama Anda" required>
                        </div>

                        <div class="form-group">
                            <label>WhatsApp / ID Telegram:</label>
                            <input type="text" name="whatsapp" class="form-control" placeholder="Contoh: 081234567890" required>
                        </div>

                        <!-- Add-ons Tambahan -->
                        <div class="form-group">
                            <label>Add-ons / Layanan Tambahan (Opsional):</label>
                            <div class="grid-options">
                                <label class="option-card">
                                    <input type="checkbox" name="addons[]" value="Garansi Anti-Lock (Rp 5.000)" data-price="5000" onclick="calculateTotal()">
                                    <div class="option-content">
                                        <strong>Garansi Anti-Lock</strong>
                                        <div style="color:var(--accent); font-size:0.85rem;">+ Rp 5.000</div>
                                    </div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="addons[]" value="Proses Prioritas Kilat (Rp 3.000)" data-price="3000" onclick="calculateTotal()">
                                    <div class="option-content">
                                        <strong>Proses Prioritas Kilat</strong>
                                        <div style="color:var(--accent); font-size:0.85rem;">+ Rp 3.000</div>
                                    </div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="addons[]" value="Private Lock PIN (Rp 10.000)" data-price="10000" onclick="calculateTotal()">
                                    <div class="option-content">
                                        <strong>Private Lock PIN</strong>
                                        <div style="color:var(--accent); font-size:0.85rem;">+ Rp 10.000</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Ringkasan Total & Dua Tombol Langsung Bayar -->
                        <div class="summary-bar" style="flex-direction: column; align-items: stretch; gap: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size:0.9rem; color:var(--text-sub);">Total Pembayaran:</span>
                                <h3 id="displayTotal" style="color:var(--accent); font-size: 1.6rem;">Rp <?php echo number_format($selectedProduct['price'], 0, ',', '.'); ?></h3>
                                <input type="hidden" name="total_harga" id="totalHargaInput" value="<?php echo $selectedProduct['price']; ?>">
                            </div>

                            <!-- Opsi Tombol Pembayaran Langsung -->
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;">
                                <button type="submit" name="payment_method" value="qris_lokal" class="btn-submit" style="background: rgba(99, 102, 241, 0.15); border: 1px solid var(--primary); color: #fff; text-align: center; padding: 14px;">
                                    <i class="fa-solid fa-qrcode"></i> QRIS Manual
                                </button>
                                
                                <button type="submit" name="payment_method" value="gateway" class="btn-submit" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; text-align: center; padding: 14px;">
                                    <i class="fa-solid fa-bolt"></i> QRIS Otomatis
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </section>

        <!-- 5. HALAMAN CONTACT -->
        <?php elseif ($page == 'contact'): ?>
            <section>
                <h2 class="section-title"><i class="fa-solid fa-headset"></i> Hubungi Kami</h2>
                <div class="info-card" style="text-align:left;">
                    <p style="margin-bottom:12px;"><strong>Email Support:</strong> Aleprem@gmail.com</p>
                    <p style="margin-bottom:12px;"><strong>Telegram Admin:</strong> @bertawbat</p>
                    <p style="margin-bottom:12px;"><strong>WhatsApp:</strong> +62 857-3347-2707</p>

                    <p><strong>Jam Operasional:</strong> 08:00 - 22:00 WIB</p>
                </div>
            </section>
        <?php endif; ?>

    </div>

    <!-- FOOTER & PAGE VIEW COUNTER -->
    <footer>
        <p>&copy; 2026 AleMium</p>
        <div class="counter-badge">
            <i class="fa-solid fa-eye"></i> Total Page Views: <strong><?php echo $views; ?></strong>
        </div>
    </footer>

    <script>
        function calculateTotal() {
            const basePrice = parseInt(document.getElementById('basePrice')?.value || 0);
            let total = basePrice;
            const selectedCheckboxes = document.querySelectorAll('input[name="addons[]"]:checked');
            selectedCheckboxes.forEach(cb => {
                total += parseInt(cb.getAttribute('data-price')) || 0;
            });
            const displayEl = document.getElementById('displayTotal');
            const inputEl = document.getElementById('totalHargaInput');
            if (displayEl) displayEl.innerText = 'Rp ' + total.toLocaleString('id-ID');
            if (inputEl) inputEl.value = total;
        }
        window.onload = calculateTotal;
    </script>
</body>
</html>