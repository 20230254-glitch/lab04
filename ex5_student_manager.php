<?php
require_once "Student.php";

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$students = [];
$error = "";
$stats = [];
$avg = $max = $min = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = trim($_POST['raw'] ?? '');
    $threshold = $_POST['threshold'] ?? '';
    $sortDesc = isset($_POST['sort_desc']);

    if ($raw === '') {
        $error = "Vui lòng nhập dữ liệu sinh viên.";
    } else {
        // ===== Parse chuỗi =====
        $records = explode(';', $raw);

        foreach ($records as $rec) {
            $parts = explode('-', trim($rec));
            if (count($parts) !== 3) continue;

            [$id, $name, $gpaStr] = $parts;
            $id = trim($id);
            $name = trim($name);
            $gpaStr = trim($gpaStr);

            if ($id === '' || $name === '' || !is_numeric($gpaStr)) continue;

            $students[] = new Student($id, $name, (float)$gpaStr);
        }

        if (count($students) === 0) {
            $error = "Không parse được sinh viên hợp lệ nào.";
        } else {
            // ===== Filter theo threshold =====
            if ($threshold !== '' && is_numeric($threshold)) {
                $students = array_filter(
                    $students,
                    fn($s) => $s->getGpa() >= (float)$threshold
                );
            }

            if (count($students) === 0) {
                $error = "Không có sinh viên nào thỏa điều kiện lọc GPA.";
            } else {
                // ===== Sort GPA giảm dần =====
                if ($sortDesc) {
                    usort($students, fn($a, $b) =>
                        $b->getGpa() <=> $a->getGpa()
                    );
                }

                // ===== Thống kê =====
                $gpas = array_map(fn($s) => $s->getGpa(), $students);
                $avg = array_sum($gpas) / count($gpas);
                $max = max($gpas);
                $min = min($gpas);

                $stats = ['Giỏi' => 0, 'Khá' => 0, 'Trung bình' => 0];
                foreach ($students as $s) {
                    $stats[$s->rank()]++;
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
    <title>Bài 5 – Student Manager</title>
    <style>
        body { font-family: Arial; background:#f3f5f7; }
        .container {
            width: 1000px; margin: 30px auto;
            background:#fff; padding:30px;
            border-radius:10px;
            box-shadow:0 8px 18px rgba(0,0,0,.1);
        }
        h2 { text-align:center; margin-bottom:25px; }
        textarea { width:100%; height:100px; }
        input[type="number"] { width:120px; }
        .btn { padding:8px 18px; background:#3498db; color:#fff; border:none; border-radius:5px; }
        .error { color:#e74c3c; font-weight:bold; margin:15px 0; }
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        th, td { padding:10px; border-bottom:1px solid #ddd; text-align:center; }
        th { background:#3498db; color:#fff; }
        .gioi{color:#27ae60;font-weight:bold;}
        .kha{color:#f39c12;font-weight:bold;}
        .tb{color:#e74c3c;font-weight:bold;}
        .box {
            margin-top:20px; padding:12px;
            background:#f9fbfd; border-left:5px solid #3498db;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🎓 Bài 5 – Student Manager (POST)</h2>

    <form method="post">
        <label><strong>Dữ liệu sinh viên:</strong></label><br>
        <textarea name="raw"><?php echo h($_POST['raw'] ?? 'SV001-An-3.2;SV002-Binh-2.6;SV003-Chi-3.5;SV004-Dung-3.8'); ?></textarea><br><br>

        GPA ≥ <input type="number" step="0.01" name="threshold" value="<?php echo h($_POST['threshold'] ?? ''); ?>">
        <label>
            <input type="checkbox" name="sort_desc" <?php if(isset($_POST['sort_desc'])) echo 'checked'; ?>>
            Sort GPA giảm dần
        </label>
        <br><br>

        <button class="btn">Parse & Show</button>
    </form>

    <?php if ($error): ?>
        <div class="error"><?php echo h($error); ?></div>
    <?php endif; ?>

    <?php if (count($students) > 0 && !$error): ?>
        <table>
            <tr>
                <th>STT</th><th>ID</th><th>Name</th><th>GPA</th><th>Rank</th>
            </tr>
            <?php foreach ($students as $i => $s):
                $r = $s->rank();
                $cls = $r==="Giỏi"?"gioi":($r==="Khá"?"kha":"tb");
            ?>
            <tr>
                <td><?php echo $i+1; ?></td>
                <td><?php echo h($s->getId()); ?></td>
                <td><?php echo h($s->getName()); ?></td>
                <td><?php echo number_format($s->getGpa(),2); ?></td>
                <td class="<?php echo $cls; ?>"><?php echo $r; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="box">
            <strong>Thống kê:</strong><br>
            GPA trung bình: <?php echo number_format($avg,2); ?><br>
            Max: <?php echo number_format($max,2); ?> |
            Min: <?php echo number_format($min,2); ?><br>
            Giỏi: <?php echo $stats['Giỏi']; ?> —
            Khá: <?php echo $stats['Khá']; ?> —
            Trung bình: <?php echo $stats['Trung bình']; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
