<?php
$nama       = $_POST["nama"] ?? "Guest";
$whatsapp   = $_POST["whatsapp"] ?? "-";
$package    = $_POST["package"] ?? "Produk";
$addons     = $_POST["addons"] ?? [];
$totalHarga = $_POST["total_harga"] ?? 0;

$invoiceID  = "STRM-" . strtoupper(substr(md5(time()), 0, 6));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS - #<?php echo $invoiceID; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-color: #0b0f19;
            --card-bg: rgba(22, 28, 45, 0.85);
            --border-color: rgba(255, 255, 255, 0.08);
            --accent: #38bdf8;
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

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            width: 100%;
            max-width: 480px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }

        .qris-box {
            background: #fff;
            padding: 15px;
            border-radius: 16px;
            display: inline-block;
            margin: 20px 0;
        }

        .qris-img {
            width: 230px;
            height: 230px;
            object-fit: contain;
            display: block;
        }

        .total-price {
            font-size: 1.6rem;
            font-weight: 800;
            color: #10b981;
            margin-bottom: 5px;
        }

        .upload-section {
            margin-top: 25px;
            text-align: left;
            background: rgba(15, 23, 42, 0.6);
            padding: 20px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
        }

        .upload-section label {
            display: block;
            font-size: 0.85rem;
            color: var(--text-sub);
            margin-bottom: 8px;
        }

        .file-input {
            width: 100%;
            color: #fff;
            font-size: 0.85rem;
        }

        .btn-confirm {
            width: 100%;
            background: #0088cc;
            color: #fff;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            margin-top: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-confirm:hover { background: #0077b5; }
    </style>
</head>
<body>

<div class="card">
    <h2>Scan QRIS Manual</h2>
    <p style="color: var(--text-sub); font-size: 0.88rem;">Invoice ID: #<?php echo $invoiceID; ?></p>

    <!-- Gambar QRIS Statis Kamu -->
    <div class="qris-box">
        <img src="qris.jpg" alt="QRIS All Payment" class="qris-img">
    </div>

    <div>
        <span style="font-size: 0.85rem; color: var(--text-sub);">Total yang Harus Dibayar:</span>
        <div class="total-price">Rp <?php echo number_format($totalHarga, 0, ',', '.'); ?></div>
        <small style="color: #9ca3af;">Bisa di-scan pake GoPay, OVO, Dana, ShopeePay, BCA, dll.</small>
    </div>

    <!-- Form Upload Bukti Transfer ke Telegram -->
    <form action="kirim_telegram.php" method="post" enctype="multipart/form-data" class="upload-section">
        <input type="hidden" name="invoice" value="<?php echo $invoiceID; ?>">
        <input type="hidden" name="nama" value="<?php echo htmlspecialchars($nama); ?>">
        <input type="hidden" name="whatsapp" value="<?php echo htmlspecialchars($whatsapp); ?>">
        <input type="hidden" name="package" value="<?php echo htmlspecialchars($package); ?>">
        <input type="hidden" name="total" value="<?php echo $totalHarga; ?>">
        
        <label><i class="fa-solid fa-receipt"></i> Upload Foto Bukti Pembayaran:</label>
        <input type="file" name="bukti" class="file-input" accept="image/*" required>

        <button type="submit" class="btn-confirm">
            <i class="fa-brands fa-telegram"></i> Kirim Bukti Pembayaran
        </button>
    </form>
</div>

</body>
</html>
