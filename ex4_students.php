<?php
require_once "Student.php";

// Hàm render an toàn
function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// ===== B1: Tạo danh sách sinh viên =====
$students = [
    new Student("SV001", "Vũ Văn Nam", 3.4),
    new Student("SV002", "Trần Thị Bình", 2.8),
    new Student("SV003", "Lê Văn Chi", 3.6),
    new Student("SV004", "Phạm Minh Dũng", 2.3),
    new Student("SV005", "Phan Đình Thi", 2.9),
];

// ===== B2: Tính GPA trung bình =====
$gpas = array_map(fn($s) => $s->getGpa(), $students);
$avgGpa = count($gpas) > 0 ? array_sum($gpas) / count($gpas) : 0;

// ===== B3: Thống kê xếp loại =====
$stats = [
    'Giỏi' => 0,
    'Khá' => 0,
    'Trung bình' => 0
];

foreach ($students as $s) {
    $stats[$s->rank()]++;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 4 – OOP Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f5f7;
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
            color: white;
        }
        tr:hover {
            background: #f9fbfd;
        }
        .gpa {
            font-weight: bold;
        }
        .gioi { color: #27ae60; font-weight: bold; }
        .kha { color: #f39c12; font-weight: bold; }
        .tb { color: #e74c3c; font-weight: bold; }

        .box {
            background: #f9fbfd;
            border-left: 5px solid #3498db;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .stat span {
            display: inline-block;
            margin-right: 15px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 25px;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🎓 Bài 4 – Danh sách sinh viên (OOP)</h2>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã SV</th>
                <th>Họ tên</th>
                <th>GPA</th>
                <th>Xếp loại</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $i => $s): 
                $rank = $s->rank();
                $class = $rank === "Giỏi" ? "gioi" : ($rank === "Khá" ? "kha" : "tb");
            ?>
            <tr>
                <td><?php echo $i + 1; ?></td>
                <td><?php echo h($s->getId()); ?></td>
                <td><?php echo h($s->getName()); ?></td>
                <td class="gpa"><?php echo number_format($s->getGpa(), 2); ?></td>
                <td class="<?php echo $class; ?>"><?php echo $rank; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="box">
        <strong>GPA trung bình của lớp:</strong>
        <?php echo number_format($avgGpa, 2); ?>
    </div>

    <div class="box stat">
        <strong>Thống kê xếp loại:</strong><br>
        <span class="gioi">Giỏi: <?php echo $stats['Giỏi']; ?></span>
        <span class="kha">Khá: <?php echo $stats['Khá']; ?></span>
        <span class="tb">Trung bình: <?php echo $stats['Trung bình']; ?></span>
    </div>

    <div class="footer">
        LAB 04 – PHP OOP & Mảng đối tượng • IT3220
    </div>
</div>

</body>
</html>
