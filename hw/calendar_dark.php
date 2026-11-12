
<?php
date_default_timezone_set("Asia/Taipei");

// 1. 取得網址的年份和月份
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date("Y");
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date("n");

// 2. 防呆
if ($year < 1970 || $year > 2100) {
    $year = (int)date("Y");
}
if ($month < 1 || $month > 12) {
    $month = (int)date("n");
}

// 3. 計算月曆
$firstDay = mktime(0, 0, 0, $month, 1, $year);
$days = (int)date("t", $firstDay);
$firstWeekday = ((int)date("w", $firstDay) + 6) % 7;
$weeks = (int)ceil(($firstWeekday + $days) / 7);

// 4. 上個月、下個月
$prev = mktime(0, 0, 0, $month - 1, 1, $year);
$next = mktime(0, 0, 0, $month + 1, 1, $year);

$prevYear = (int)date("Y", $prev);
$prevMonth = (int)date("n", $prev);
$nextYear = (int)date("Y", $next);
$nextMonth = (int)date("n", $next);

$hasPrev = $prevYear >= 1970;
$hasNext = $nextYear <= 2100;

// 5. 今天
$isThisMonth = (
    $year == (int)date("Y") &&
    $month == (int)date("n")
);
$todayDay = (int)date("j");

$names = ['一', '二', '三', '四', '五', '六', '日'];

// 6. 月相近似計算
function getMoonPhase($year, $month, $day)
{
    $reference = gmmktime(18, 14, 0, 1, 6, 2000);
    $current = gmmktime(4, 0, 0, $month, $day, $year);

    $cycle = 29.530588853;
    $age = ($current - $reference) / 86400;
    $phase = fmod($age, $cycle);

    if ($phase < 0) {
        $phase += $cycle;
    }

    return (int)floor($phase / $cycle * 8 + 0.5) % 8;
}

$phaseNames = [
    "新月",
    "盈眉月",
    "上弦月",
    "盈凸月",
    "滿月",
    "虧凸月",
    "下弦月",
    "殘月"
];
?>

<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>月蝕魔法萬年曆</title>

    <!-- 萬年曆樣式 -->
    <link rel="stylesheet" href="style_dark.css">

    <!-- Cookie 寵物樣式 -->
    <link rel="stylesheet" href="./images/cookie/cookie_pet.css">
</head>

<body class="theme-dark">

    <main class="page">

        <header class="hero">
            <p class="eyebrow">✦ LUNAR ARCANA ✦</p>
            <h1>☾ 月蝕魔法萬年曆 ☽</h1>
            <p class="subtitle">
                在月光中，記錄每一天的魔法
            </p>
        </header>

        <!-- 上一月、下一月 -->
        <nav class="nav">

            <?php if ($hasPrev): ?>
                <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>">
                    ◀ 上一個月
                </a>
            <?php else: ?>
                <span class="nav-disabled">◀ 上一個月</span>
            <?php endif; ?>

            <h2><?= $year ?> 年 <?= $month ?> 月</h2>

            <?php if ($hasNext): ?>
                <a href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>">
                    下一個月 ▶
                </a>
            <?php else: ?>
                <span class="nav-disabled">下一個月 ▶</span>
            <?php endif; ?>

        </nav>

        <!-- 萬年曆 -->
        <div class="calendar-wrap">
            <table class="calendar">

                <thead>
                    <tr>
                        <?php foreach ($names as $i => $name): ?>
                            <th class="<?= $i >= 5 ? 'weekend' : '' ?>">
                                <?= $name ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php for ($i = 0; $i < $weeks; $i++): ?>
                        <tr>
                            <?php for ($j = 0; $j < 7; $j++): ?>
                                <?php
                                $day = $i * 7 + $j - $firstWeekday + 1;
                                ?>

                                <?php if ($day >= 1 && $day <= $days): ?>
                                    <?php
                                    $phase = getMoonPhase($year, $month, $day);
                                    $classes = ['day'];

                                    if ($j >= 5) {
                                        $classes[] = 'weekend';
                                    }

                                    if ($isThisMonth && $day == $todayDay) {
                                        $classes[] = 'today';
                                    }
                                    ?>

                                    <td class="<?= implode(' ', $classes) ?>"
                                        title="<?= $phaseNames[$phase] ?>">

                                        <div class="moon phase-<?= $phase ?>">
                                            <span class="day-number">
                                                <?= $day ?>
                                            </span>
                                        </div>

                                    </td>

                                <?php else: ?>
                                    <td class="empty"></td>
                                <?php endif; ?>

                            <?php endfor; ?>
                        </tr>
                    <?php endfor; ?>
                </tbody>

            </table>
        </div>

        <p class="moon-note">
            ☾ 月相為近似模擬，非精密天文預報 ☽
        </p>

        <footer>
            <a href="index.html">⌂ 返回首頁</a>
        </footer>

    </main>

    <!-- Cookie 網頁寵物 -->
    <div id="cookie-pet">
        <div class="cookie-speech" hidden></div>

        <img
            id="cookie-image"
            src="./images/cookie/cookie_blink.gif"
            alt="巧克力色長毛吉娃娃 Cookie"
        >
    </div>

    <!-- Cookie 互動程式 -->
    <script src="./images/cookie/cookie_pet.js"></script>

</body>
</html>
