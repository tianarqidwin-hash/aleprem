<?php
// Menerima POST dari form index.php
$nama       = $_POST["nama"] ?? "Guest";
$whatsapp   = $_POST["whatsapp"] ?? "-"; // nomor kontak / WA / Telegram ID
$package    = $_POST["package"] ?? "Tidak memilih produk";
$addons     = $_POST["addons"] ?? [];
$totalHarga = $_POST["total_harga"] ?? 0;

// Format Invoice ID Unik
$invoiceID  = "STRM-" . strtoupper(substr(md5(time()), 0, 6));

// ========================================================
// CONFIG TELEGRAM ADMIN
// ========================================================
$telegramUsername = "UsernameTelegramKamu"; // GANTI dengan username Telegram admin tanpa @

// Format Pesan Telegram
$pesanTG  = "🛒 *KONFIRMASI PEMESANAN STREAMVAULT*\n";
$pesanTG .= "-------------------------------------\n";
$pesanTG .= "🆔 *Invoice:* #$invoiceID\n";
$pesanTG .= "👤 *Nama:* $nama\n";
$pesanTG .= "📱 *Kontak:* $whatsapp\n\n";
$pesanTG .= "📦 *Produk Dipilih:*\n";
$pesanTG .= "▫️ $package\n\n";
$pesanTG .= "➕ *Add-ons / Tambahan:*\n";

if (count($addons) > 0) {
    foreach ($addons as $item) {
        $pesanTG .= "• $item\n";
    }
} else {
    $pesanTG .= "• Tidak ada\n";
}

$pesanTG .= "\n💰 *Total Bayar:* Rp " . number_format($totalHarga, 0, ',', '.') . "\n";
$pesanTG .= "-------------------------------------\n";
$pesanTG .= "Halo Admin, mohon proses pesanan dan berikan instruksi pembayarannya. Terima kasih!";

// Buat URL Deep Link Telegram (t.me/username?text=...)
$urlTelegram = "https://t.me/bertawbat" . "?text=" . urlencode($pesanTG);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pemesanan #<?php echo $invoiceID; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-color: #0b0f19;
            --card-bg: rgba(22, 28, 45, 0.85);
            --border-color: rgba(255, 255, 255, 0.08);
            --accent: #0088cc; /* Telegram Color Theme */
            --accent-hover: #0077b5;
            --text-main: #f3f4f6;
            --text-sub: #9ca3af;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .invoice-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            width: 100%;
            max-width: 550px;
            padding: 35px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
            backdrop-filter: blur(10px);
        }

        .status-badge {
            text-align: center;
            margin-bottom: 25px;
        }

        .status-icon {
            width: 65px;
            height: 65px;
            background: rgba(0, 136, 204, 0.15);
            color: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 12px;
            border: 1px solid rgba(0, 136, 204, 0.3);
        }

        .invoice-id {
            font-size: 0.85rem;
            color: var(--text-sub);
            letter-spacing: 1px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed var(--border-color);
            font-size: 0.95rem;
        }

        .detail-row .label { color: var(--text-sub); }
        .detail-row .value { font-weight: 600; text-align: right; }

        .addons-box {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 12px;
            padding: 12px;
            margin-top: 5px;
        }

        .addons-box li {
            list-style: none;
            font-size: 0.88rem;
            color: var(--text-sub);
            margin-bottom: 4px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 15px;
        }

        .total-row h3 { font-size: 1.5rem; color: #38bdf8; }

        .btn-telegram {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            background: var(--accent);
            color: #fff;
            text-decoration: none;
            padding: 16px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1rem;
            margin-top: 25px;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(0, 136, 204, 0.3);
        }

        .btn-telegram:hover {
            transform: translateY(-2px);
            background: var(--accent-hover);
            box-shadow: 0 15px 25px rgba(0, 136, 204, 0.4);
        }

        .btn-back {
            display: block;
            text-align: center;
            color: var(--text-sub);
            text-decoration: none;
            margin-top: 15px;
            font-size: 0.88rem;
        }
    </style>
</head>
<body>

<div class="invoice-card">
    <div class="status-badge">
        <div class="status-icon"><i class="fa-paper-plane fa-brands"></i></div>
        <h2>Pesanan Siap Dikirim</h2>
        <span class="invoice-id">INVOICE: #<?php echo $invoiceID; ?></span>
    </div>

    <!-- Details -->
    <div class="detail-row">
        <span class="label">Nama Pemesan</span>
        <span class="value"><?php echo htmlspecialchars($nama); ?></span>
    </div>

    <div class="detail-row">
        <span class="label">Kontak / No. HP</span>
        <span class="value"><?php echo htmlspecialchars($whatsapp); ?></span>
    </div>

    <div class="detail-row">
        <span class="label">Aplikasi Dipilih</span>
        <span class="value" style="color: #38bdf8;"><?php echo htmlspecialchars($package); ?></span>
    </div>

    <div style="margin-top: 15px;">
        <span class="label" style="font-size:0.85rem; color:var(--text-sub);">Add-ons / Layanan Tambahan:</span>
        <div class="addons-box">
            <?php if (count($addons) > 0): ?>
                <?php foreach ($addons as $item): ?>
                    <li><i class="fa-solid fa-plus-circle" style="color:var(--accent);"></i> <?php echo htmlspecialchars($item); ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Tidak ada add-ons tambahan.</li>
            <?php endif; ?>
        </div>
    </div>

    <div class="total-row">
        <span>Total Pembayaran</span>
        <h3>Rp <?php echo number_format($totalHarga, 0, ',', '.'); ?></h3>
    </div>

    <!-- Direct Checkout ke Telegram -->
    <a href="<?php echo $urlTelegram; ?>" target="_blank" class="btn-telegram">
        <i class="fa-brands fa-telegram" style="font-size: 1.3rem;"></i> Konfirmasi via Telegram
    </a>

    <a href="index.html" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Toko</a>
</div>

</body>
</html>