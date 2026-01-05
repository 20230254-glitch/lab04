<?php
// ===== B1: Khai báo mảng điểm =====
$scores = [8.5, 7.0, 9.25, 6.5, 8.0, 5.75];

// ===== B2: Tính trung bình =====
$total = array_sum($scores);
$count = count($scores);
$average = $count > 0 ? $total / $count : 0;

// ===== B3: Lọc điểm >= 8 =====
$goodScores = array_filter($scores, fn($s) => $s >= 8.0);

// ===== B4: Max / Min =====
$maxScore = max($scores);
$minScore = min($scores);

// ===== B5: Sắp xếp =====
$ascScores  = $scores;
$descScores = $scores;

sort($ascScores);
rsort($descScores);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 2 – Thống kê mảng điểm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }
        .container {
            width: 700px;
            margin: 40px auto;
            background: #ffffff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }
        .box {
            background: #f9fbfd;
            border-left: 5px solid #3498db;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .highlight {
            color: #e74c3c;
            font-weight: bold;
        }
        ul {
            margin: 8px 0 0 20px;
        }
        .tag {
            display: inline-block;
            padding: 4px 10px;
            background: #3498db;
            color: white;
            border-radius: 12px;
            margin: 3px 5px 3px 0;
            font-size: 14px;
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
    <h2>📊 Bài 2 – Thống kê mảng điểm</h2>

    <div class="box">
        <strong>Mảng điểm ban đầu:</strong><br>
        <?php foreach ($scores as $s): ?>
            <span class="tag"><?php echo htmlspecialchars($s); ?></span>
        <?php endforeach; ?>
    </div>

    <div class="box">
        <strong>Điểm trung bình:</strong>
        <span class="highlight">
            <?php echo number_format($average, 2); ?>
        </span>
    </div>

    <div class="box">
        <strong>Điểm ≥ 8.0 (<?php echo count($goodScores); ?> điểm):</strong>
        <ul>
            <?php foreach ($goodScores as $s): ?>
                <li><?php echo htmlspecialchars($s); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="box">
        <strong>Điểm cao nhất:</strong> <?php echo htmlspecialchars($maxScore); ?><br>
        <strong>Điểm thấp nhất:</strong> <?php echo htmlspecialchars($minScore); ?>
    </div>

    <div class="box">
        <strong>Sắp xếp tăng dần:</strong><br>
        <?php echo htmlspecialchars(implode(', ', $ascScores)); ?>
    </div>

    <div class="box">
        <strong>Sắp xếp giảm dần:</strong><br>
        <?php echo htmlspecialchars(implode(', ', $descScores)); ?>
    </div>

    <div class="footer">
        LAB 04 – PHP Mảng & Thống kê • IT3220
    </div>
</div>

</body>
</html>
