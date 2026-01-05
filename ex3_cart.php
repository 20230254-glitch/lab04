<?php
// ===== Hàm render an toàn =====
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// ===== B1: Khai báo mảng sản phẩm =====
$products = [
    ['name' => 'Bút bi',     'price' => 5000,  'qty' => 3],
    ['name' => 'Vở ô ly',    'price' => 12000, 'qty' => 5],
    ['name' => 'Thước kẻ',   'price' => 8000,  'qty' => 2],
    ['name' => 'Balo',       'price' => 250000,'qty' => 1],
];

// ===== B2: Thêm cột amount =====
$productsWithAmount = array_map(function ($p) {
    $p['amount'] = $p['price'] * $p['qty'];
    return $p;
}, $products);

// ===== B3: Tính tổng tiền =====
$totalAmount = array_reduce($productsWithAmount, function ($sum, $p) {
    return $sum + $p['amount'];
}, 0);

// ===== B4: Tìm sản phẩm có amount lớn nhất =====
$maxProduct = $productsWithAmount[0];
foreach ($productsWithAmount as $p) {
    if ($p['amount'] > $maxProduct['amount']) {
        $maxProduct = $p;
    }
}

// ===== B5: Sort theo price giảm dần =====
$sortedProducts = $productsWithAmount;
usort($sortedProducts, function ($a, $b) {
    return $b['price'] <=> $a['price'];
});
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 3 – Giỏ hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f7;
        }
        .container {
            width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 18px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #3498db;
            color: #fff;
        }
        tr:hover {
            background: #f9fbfd;
        }
        .right {
            text-align: right;
        }
        .total {
            font-weight: bold;
            color: #e74c3c;
        }
        .highlight {
            background: #fef3c7;
            padding: 12px;
            border-left: 5px solid #f59e0b;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .section-title {
            margin: 25px 0 10px;
            color: #2c3e50;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🛒 Bài 3 – Giỏ hàng</h2>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Đơn giá (VNĐ)</th>
                <th>Số lượng</th>
                <th>Thành tiền (VNĐ)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productsWithAmount as $i => $p): ?>
                <tr>
                    <td><?php echo $i + 1; ?></td>
                    <td><?php echo h($p['name']); ?></td>
                    <td class="right"><?php echo number_format($p['price']); ?></td>
                    <td><?php echo h($p['qty']); ?></td>
                    <td class="right"><?php echo number_format($p['amount']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="right total">Tổng tiền đơn hàng:</td>
                <td class="right total"><?php echo number_format($totalAmount); ?> VNĐ</td>
            </tr>
        </tfoot>
    </table>

    <div class="highlight">
        <strong>Sản phẩm có thành tiền lớn nhất:</strong><br>
        <?php echo h($maxProduct['name']); ?> —
        <?php echo number_format($maxProduct['amount']); ?> VNĐ
    </div>

    <h3 class="section-title">📉 Danh sách sau khi sắp xếp theo giá giảm dần</h3>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Đơn giá (VNĐ)</th>
                <th>Số lượng</th>
                <th>Thành tiền (VNĐ)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sortedProducts as $i => $p): ?>
                <tr>
                    <td><?php echo $i + 1; ?></td>
                    <td><?php echo h($p['name']); ?></td>
                    <td class="right"><?php echo number_format($p['price']); ?></td>
                    <td><?php echo h($p['qty']); ?></td>
                    <td class="right"><?php echo number_format($p['amount']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
