
<?php
require_once "Product.php";

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$products = [];
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = trim($_POST['data'] ?? '');
    $minPrice = $_POST['minPrice'] ?? '';
    $sortAmount = isset($_POST['sortAmount']);

    $records = explode(";", $raw);

    foreach ($records as $rec) {
        $rec = trim($rec);
        if ($rec === '') continue;

        $parts = explode("-", $rec);
        if (count($parts) !== 4) continue;

        [$id, $name, $price, $qty] = $parts;

        if (!is_numeric($price) || !is_numeric($qty)) continue;

        $products[] = new Product($id, $name, $price, $qty);
    }

    if (empty($products)) {
        $error = "⚠️ Không có dữ liệu sản phẩm hợp lệ.";
    }

    // Filter theo minPrice
    if ($minPrice !== '' && is_numeric($minPrice)) {
        $products = array_filter($products, fn($p) => $p->getPrice() >= $minPrice);
    }

    // Sort theo amount giảm dần
    if ($sortAmount) {
        usort($products, fn($a, $b) => $b->amount() <=> $a->amount());
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mini Sales Manager</title>
    <style>
        body { font-family: Arial; background: #f4f6f8; }
        .box { max-width: 900px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 8px; }
        h2 { color: #2c3e50; }
        textarea { width: 100%; height: 80px; }
        input[type=number] { width: 150px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background: #3498db; color: white; }
        .invalid { color: red; font-weight: bold; }
        .summary { margin-top: 15px; background: #ecf0f1; padding: 10px; border-radius: 6px; }
        button { padding: 8px 16px; background: #27ae60; color: white; border: none; border-radius: 4px; }
    </style>
</head>
<body>

<div class="box">
    <h2>🛒 Mini Sales Manager</h2>

    <form method="post" id="salesForm">
    <label>Dữ liệu sản phẩm:</label>

    <textarea name="data" id="dataTextarea"><?= h($_POST['data'] ?? 
'SP001-Nồi cơm điện-1200000-2;
SP002-Ấm siêu tốc-450000-1;
SP003-Máy xay sinh tố-800000-3;
SP004-Phích-650000-0'
) ?></textarea>

    <p>
        Min Price:
        <input type="number" step="0.01" name="minPrice"
               value="<?= h($_POST['minPrice'] ?? '') ?>">

        <label>
            <input type="checkbox" name="sortAmount"
                <?= isset($_POST['sortAmount']) ? 'checked' : '' ?>>
            Sort Amount giảm dần
        </label>
    </p>

    <!-- Button KHÔNG submit trực tiếp -->
    <button type="button" onclick="submitForm()">Parse & Show</button>
</form>

    <?php if ($error): ?>
        <p class="invalid"><?= h($error) ?></p>
    <?php endif; ?>

    <?php if (!empty($products)): ?>
        <table>
            <tr>
                <th>STT</th><th>ID</th><th>Name</th><th>Price</th><th>Qty</th><th>Amount</th>
            </tr>

            <?php
            $total = 0;
            $maxAmount = 0;
            $sumPrice = 0;

            foreach ($products as $i => $p):
                $amount = $p->amount();
                $total += $amount;
                $sumPrice += $p->getPrice();
                $maxAmount = max($maxAmount, $amount);
            ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= h($p->getId()) ?></td>
                <td><?= h($p->getName()) ?></td>
                <td><?= number_format($p->getPrice(), 2) ?></td>
                <td class="<?= $p->isValidQty() ? '' : 'invalid' ?>">
                    <?= $p->isValidQty() ? $p->getQty() : 'Invalid qty' ?>
                </td>
                <td><?= number_format($amount, 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="summary">
            <p>💰 <b>Tổng tiền:</b> <?= number_format($total, 2) ?></p>
            <p>🔥 <b>Amount lớn nhất:</b> <?= number_format($maxAmount, 2) ?></p>
            <p>📊 <b>Avg price:</b> <?= number_format($sumPrice / count($products), 2) ?></p>
        </div>
    <?php endif; ?>
</div>
<script>
function submitForm() {
    document.getElementById('salesForm').submit();
}

// Chặn Enter submit khi đang gõ textarea
document.getElementById('dataTextarea').addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
        e.stopPropagation(); // cho xuống dòng nhưng không submit
    }
});
</script>

</body>
</html>
