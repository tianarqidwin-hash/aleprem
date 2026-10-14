<?php
// Aktifkan error handling sederhana agar tidak terjadi layar putih polos
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Menerima Data POST dari index.php
$nama          = htmlspecialchars($_POST["nama"] ?? "Pelanggan");
$whatsapp      = htmlspecialchars($_POST["whatsapp"] ?? "-");
$package       = htmlspecialchars($_POST["package"] ?? "Produk Premium");
$addons        = $_POST["addons"] ?? [];
$totalHarga    = (int)($_POST["total_harga"] ?? 0);
$paymentMethod = $_POST["payment_method"] ?? "qris_lokal"; // Menerima nilai dari tombol yang diklik

// Generate ID Invoice Unik
$invoiceID  = "STRM-" . strtoupper(substr(md5(time()), 0, 6));

// =======================================================
// KREDENSIAL & LOGIKA PAKASIR / QRIS LOKAL
// =======================================================
$slug          = 'ale-auto-order-24-hours';
$apiKey        = '4FeR07YtTtTsMmnVuULM8OvAjtE5HrGj';
$qrisImage     = "";
$isMaintenance = false;

if ($paymentMethod === 'gateway') {
    // Mencoba panggil API Pakasir
    if (function_exists('curl_init')) {
        $apiUrl = "https://pakkasir.com/api/v1/transaction/create";
        $payload = [
            'slug'          => $slug,
            'amount'        => $totalHarga,
            'order_id'      => $invoiceID,
            'customer_name' => $nama
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                "Content-Type: application/json",
                "Authorization: Bearer " . $apiKey
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 4
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $result = json_decode($response, true);
            if (!empty($result['data']['qris_url'])) {
                $qrisImage = $result['data']['qris_url'];
            } elseif (!empty($result['data']['payment_number'])) {
                $qrisImage = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($result['data']['payment_number']);
            }
        }
    }

    // Jika API Pakasir gagal / timeout -> Set Maintenance & Alihkan ke QRIS Lokal
    if (empty($qrisImage)) {
        $isMaintenance = true;
        $qrisImage     = "qris.jpg.jpeg";
    }
} else {
    // Jika pembeli memilih tombol QRIS Manual (Lokal)
    $qrisImage = "qris.jpg.jpeg";
}

// Format Pesan Telegram Admin
$telegramAdmin = "UsernameTelegramKamu"; // Ganti dengan username Telegram tanpa @
$pesanTG  = "🛒 *PEMESANAN BARU STREAMVAULT*\n";
$pesanTG .= "----------------------------------\n";
$pesanTG .= "🆔 Invoice: #$invoiceID\n";
$pesanTG .= "👤 Nama: $nama\n";
$pesanTG .= "📱 WhatsApp: $whatsapp\n";
$pesanTG .= "📦 Produk: $package\n";
$pesanTG .= "💳 Metode: " . ($paymentMethod === 'gateway' ? 'Pakasir Gateway' : 'QRIS Manual') . "\n";
$pesanTG .= "💰 Total Tagihan: Rp " . number_format($totalHarga, 0, ',', '.') . "\n";
$pesanTG .= "----------------------------------\n";
$pesanTG .= "Halo Admin, saya telah melakukan pemesanan dan ingin konfirmasi.";

$urlTelegram = "https://t.me/" . $telegramAdmin . "?text=" . urlencode($pesanTG);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pembayaran #<?php echo $invoiceID; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body style="padding: 20px;">

    <div class="invoice-wrapper">
        
        <!-- Header Invoice -->
        <div class="invoice-header">
            <span class="invoice-badge">
                <i class="fa-solid fa-credit-card"></i> 
                <?php echo ($paymentMethod === 'gateway' && !$isMaintenance) ? 'Pakasir Automated QRIS' : 'QRIS Manual (Lokal)'; ?>
            </span>
            <h2 style="font-size: 1.5rem; margin-top: 5px;">Rincian Pembayaran</h2>
            <div class="invoice-id">#<?php echo $invoiceID; ?></div>
        </div>

        <!-- Detail Pemesan & Produk -->
        <div class="invoice-table">
            <div class="invoice-row">
                <span class="label">Nama Pemesan</span>
                <span class="value"><?php echo $nama; ?></span>
            </div>
            <div class="invoice-row">
                <span class="label">Nomor WhatsApp</span>
                <span class="value"><?php echo $whatsapp; ?></span>
            </div>
            <div class="invoice-row">
                <span class="label">Produk Utama</span>
                <span class="value" style="color: var(--primary);"><?php echo $package; ?></span>
            </div>
            <div class="invoice-row" style="flex-direction: column; align-items: flex-start; gap: 5px;">
                <span class="label">Layanan Tambahan (Add-ons):</span>
                <div class="addons-tag-list" style="width: 100%;">
                    <?php if (count($addons) > 0): ?>
                        <?php foreach ($addons as $item): ?>
                            <span class="addon-tag">+ <?php echo htmlspecialchars($item); ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span style="font-size:0.85rem; color:var(--text-sub);">Tidak ada add-ons</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Ringkasan Total Biaya -->
        <div class="invoice-total">
            <span style="color: var(--text-sub); font-size: 0.9rem;">Total Pembayaran:</span>
            <div class="price">Rp <?php echo number_format($totalHarga, 0, ',', '.'); ?></div>
        </div>

        <!-- QRIS Section -->
        <div class="qris-section">
            
            <!-- Alert jika memilih Pakasir tetapi API bermasalah/offline -->
            <?php if ($isMaintenance): ?>
                <div style="padding: 12px 16px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); border-radius: 12px; margin-bottom: 15px; text-align: center;">
                    <i class="fa-solid fa-wrench" style="color: #ef4444; font-size: 1.2rem; margin-bottom: 5px;"></i>
                    <p style="color: #f87171; font-size: 0.88rem; font-weight: 700; margin: 0;">Payment Gateway Sedang Maintenance</p>
                    <span style="color: var(--text-sub); font-size: 0.78rem;">Pembayaran otomatis dialihkan ke QRIS Manual di bawah ini.</span>
                </div>
            <?php endif; ?>

            <p style="font-size: 0.88rem; color: var(--text-sub);">Scan QRIS Di Bawah Ini Untuk Membayar:</p>
            <div class="qris-frame">
                <img src="<?php echo $qrisImage; ?>" alt="QRIS Payment">
            </div>
            <p style="font-size: 0.8rem; color: var(--text-sub);">Dapat di-scan dengan GoPay, OVO, Dana, ShopeePay, & All m-Banking</p>
        </div>

        <!-- Tombol Konfirmasi Telegram VIP -->
        <a href="<?php echo $urlTelegram; ?>" target="_blank" class="btn-telegram-vip">
            <div class="tg-btn-content">
                <div class="tg-icon-wrapper">
                    <i class="fa-brands fa-telegram"></i>
                </div>
                <div class="tg-text-group">
                    <span class="tg-title">Konfirmasi via Telegram</span>
                    <span class="tg-subtitle">⚡ Diproses Otomatis &lt; 2 Menit</span>
                </div>
            </div>
            <div class="tg-arrow">
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <div style="text-align: center; margin-top: 15px;">
            <a href="index.php?page=products" style="color: var(--text-sub); text-decoration: none; font-size: 0.88rem;">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog Produk
            </a>
        </div>

    </div>

</body>
</html>