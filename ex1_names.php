<?php
// B1: Lấy dữ liệu từ GET
$rawNames = $_GET['names'] ?? '';

// Khởi tạo mảng tên hợp lệ
$validNames = [];

if (!empty($rawNames)) {
    // B2: Tách chuỗi theo dấu phẩy
    $parts = explode(',', $rawNames);

    // B3: trim từng phần tử
    $parts = array_map('trim', $parts);

    // B4: loại bỏ phần tử rỗng
    $validNames = array_filter($parts, function ($name) {
        return $name !== '';
    });
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 1 – Danh sách tên</title>
</head>
<body>

<h2>Bài 1 – Chuỗi → Danh sách tên (GET)</h2>

<p><strong>Chuỗi gốc:</strong>
    <?php
    echo htmlspecialchars($rawNames);
    ?>
</p>

<?php if (count($validNames) > 0): ?>
    <p><strong>Số lượng tên hợp lệ:</strong>
        <?php echo count($validNames); ?>
    </p>

    <ol>
        <?php foreach ($validNames as $name): ?>
            <li><?php echo htmlspecialchars($name); ?></li>
        <?php endforeach; ?>
    </ol>
<?php else: ?>
    <p style="color:red;">Chưa có dữ liệu hợp lệ</p>
<?php endif; ?>

</body>
</html>
