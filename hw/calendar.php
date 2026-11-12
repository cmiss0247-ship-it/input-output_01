<?php
// 1. 取得網址傳來的年份、月份
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date("Y");
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date("n");

// 2. 檢查年份和月份
if ($year < 1970 || $year > 2100) {
    $year = (int)date("Y");
}

if ($month < 1 || $month > 12) {
    $month = (int)date("n");
}

// 3. 計算這個月的第一天
$firstDay = mktime(0, 0, 0, $month, 1, $year);

// 這個月1號是星期幾？0代表星期日
$firstWeekday = (int)date("w", $firstDay);

// 這個月總共有幾天？
$days = (int)date("t", $firstDay);

// 需要幾列才能放完這個月？
$weeks = (int)ceil(($firstWeekday + $days) / 7);

// 4. 計算上個月和下個月
$prev = mktime(0, 0, 0, $month - 1, 1, $year);
$next = mktime(0, 0, 0, $month + 1, 1, $year);
?>

<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>我的萬年曆</title>
</head>

<body class="theme-light">
    <h1>我的萬年曆</h1>

    <a href="?year=<?= date('Y', $prev) ?>&month=<?= date('n', $prev) ?>">
        ◀ 上一個月
    </a>

    <h2><?= $year ?> 年 <?= $month ?> 月</h2>

    <a href="?year=<?= date('Y', $next) ?>&month=<?= date('n', $next) ?>">
        下一個月 ▶
    </a>

    <table class="calendar">
        <tr>
            <th>日</th>
            <th>一</th>
            <th>二</th>
            <th>三</th>
            <th>四</th>
            <th>五</th>
            <th>六</th>
        </tr>

        <?php
        // 外層迴圈：控制星期列數
        for ($i = 0; $i < $weeks; $i++) {
            echo "<tr>";

            // 內層迴圈：每列有7天
            for ($j = 0; $j < 7; $j++) {

                // 算出這一格的日期
                $day = $i * 7 + $j - $firstWeekday + 1;

                if ($day >= 1 && $day <= $days) {
                    echo "<td>$day</td>";
                } else {
                    echo "<td></td>";
                }
            }

            echo "</tr>";
        }
        ?>
    </table>

    <p><a href="index.html">返回首頁</a></p>
</body>

</html>