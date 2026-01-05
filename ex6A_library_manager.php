<?php
require_once "Book.php";

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$books = [];
$error = "";

$totalTitles = $totalQty = $outOfStock = 0;
$maxQtyBook = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = trim($_POST['raw'] ?? '');
    $q = trim($_POST['q'] ?? '');
    $sortQtyDesc = isset($_POST['sort_qty']);

    if ($raw === '') {
        $error = "Vui lòng nhập dữ liệu sách.";
    } else {
        // ===== Parse dữ liệu =====
        $records = explode(';', $raw);

        foreach ($records as $rec) {
            $parts = explode('-', trim($rec));
            if (count($parts) !== 3) continue;

            [$id, $title, $qtyStr] = $parts;
            $id = trim($id);
            $title = trim($title);
            $qtyStr = trim($qtyStr);

            if ($id === '' || $title === '' || !is_numeric($qtyStr)) continue;

            $books[] = new Book($id, $title, (int)$qtyStr);
        }

        if (count($books) === 0) {
            $error = "Không có bản ghi sách hợp lệ.";
        } else {
            // ===== Tìm theo title =====
            if ($q !== '') {
                $books = array_filter($books, function ($b) use ($q) {
                    return stripos($b->getTitle(), $q) !== false;
                });
            }

            if (count($books) === 0) {
                $error = "Không tìm thấy sách phù hợp.";
            } else {
                // ===== Sort qty giảm dần =====
                if ($sortQtyDesc) {
                    usort($books, fn($a, $b) =>
                        $b->getQty() <=> $a->getQty()
                    );
                }

                // ===== Thống kê =====
                $totalTitles = count($books);
                foreach ($books as $b) {
                    $totalQty += $b->getQty();
                    if ($b->getQty() === 0) $outOfStock++;

                    if ($maxQtyBook === null || $b->getQty() > $maxQtyBook->getQty()) {
                        $maxQtyBook = $b;
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 6A – Library Manager</title>
    <style>
        body { font-family: Arial; background:#f3f5f7; }
        .container {
            width: 1000px;
            margin: 30px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 18px rgba(0,0,0,.1);
        }
        h2 { text-align:center; margin-bottom:25px; }
        textarea { width:100%; height:90px; }
        input[type="text"] { width:220px; }
        .btn {
            padding:8px 18px;
            background:#3498db;
            color:#fff;
            border:none;
            border-radius:5px;
        }
        .error {
            margin:15px 0;
            color:#e74c3c;
            font-weight:bold;
        }
        table {
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }
        th, td {
            padding:10px;
            border-bottom:1px solid #ddd;
            text-align:center;
        }
        th { background:#3498db; color:#fff; }
        .available { color:#27ae60; font-weight:bold; }
        .out { color:#e74c3c; font-weight:bold; }
        .box {
            margin-top:20px;
            padding:12px;
            background:#f9fbfd;
            border-left:5px solid #3498db;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📚 Bài 6A – Mini Library Manager</h2>

    <form method="post">
        <label><strong>Dữ liệu sách:</strong></label><br>
        <textarea name="raw"><?php
            echo h($_POST['raw'] ?? 'S001-Lập trình PHP cơ bản-2;
S002-Lập trình Web nâng cao-5;
S003-Cơ sở dữ liệu-1;
S004-Giáo trình cũ-0
');
        ?></textarea><br><br>

        Tìm theo tiêu đề:
        <input type="text" name="q" value="<?php echo h($_POST['q'] ?? ''); ?>">

        <label>
            <input type="checkbox" name="sort_qty" <?php if(isset($_POST['sort_qty'])) echo 'checked'; ?>>
            Sort Qty giảm dần
        </label>

        <br><br>
        <button class="btn">Parse & Show</button>
    </form>

    <?php if ($error): ?>
        <div class="error"><?php echo h($error); ?></div>
    <?php endif; ?>

    <?php if (count($books) > 0 && !$error): ?>
        <table>
            <tr>
                <th>STT</th>
                <th>Book ID</th>
                <th>Title</th>
                <th>Qty</th>
                <th>Status</th>
            </tr>
            <?php foreach ($books as $i => $b): ?>
                <tr>
                    <td><?php echo $i+1; ?></td>
                    <td><?php echo h($b->getId()); ?></td>
                    <td><?php echo h($b->getTitle()); ?></td>
                    <td><?php echo $b->getQty(); ?></td>
                    <td class="<?php echo $b->getQty() > 0 ? 'available' : 'out'; ?>">
                        <?php echo $b->getStatus(); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="box">
            <strong>Thống kê:</strong><br>
            Tổng đầu sách: <?php echo $totalTitles; ?><br>
            Tổng số quyển: <?php echo $totalQty; ?><br>
            Sách nhiều nhất: <?php echo h($maxQtyBook->getTitle()); ?>
            (<?php echo $maxQtyBook->getQty(); ?> quyển)<br>
            Số sách Out of stock: <?php echo $outOfStock; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
