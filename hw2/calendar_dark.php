<?php
date_default_timezone_set("Asia/Taipei");

// 1. 取得網址的年份、月份、選取的日期
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date("Y");
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date("n");

// 2. 防呆
if ($year < 1970 || $year > 2100) {
    $year = (int)date("Y");
}
if ($month < 1 || $month > 12) {
    $month = (int)date("n");
}

// 3. 計算月曆（星期日開頭）
$firstDay = mktime(0, 0, 0, $month, 1, $year);
$days = (int)date("t", $firstDay);
$firstWeekday = (int)date("w", $firstDay);
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
$isThisMonth = ($year == (int)date("Y") && $month == (int)date("n"));
$todayDay = (int)date("j");

// 6. 右側要顯示的「所選日期」：網址有 day 就用它，否則本月用今天、其他月份用 1 號
$selected = isset($_GET['day']) ? (int)$_GET['day'] : ($isThisMonth ? $todayDay : 1);
if ($selected < 1 || $selected > $days) {
    $selected = 1;
}

$names = ['日', '一', '二', '三', '四', '五', '六'];
$monthNames = [
    1 => 'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE',
    'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'
];

// 7. 月相近似計算（0 ~ 7）
function getMoonPhase($year, $month, $day)
{
    $reference = gmmktime(18, 14, 0, 1, 6, 2000);   // 已知的一次新月
    $current = gmmktime(4, 0, 0, $month, $day, $year);

    $cycle = 29.530588853;                          // 朔望月天數
    $age = ($current - $reference) / 86400;
    $phase = fmod($age, $cycle);

    if ($phase < 0) {
        $phase += $cycle;
    }

    return (int)floor($phase / $cycle * 8 + 0.5) % 8;
}

$phaseNames = ["新月", "眉月", "上弦月", "盈凸月", "滿月", "虧凸月", "下弦月", "殘月"];

// 8. 每個月相對應的圖片（images/month2）
$phaseFiles = [
    "紫色新月星環-2.png",
    "紫色眉月銀色星環-3.png",
    "紫色上弦月星環-1.png",
    "紫色盈凸月與星環-5.png",
    "紫色滿月星環-4.png",
    "紫色虧凸月鍍鉻星環-9.png",
    "紫色下弦月與鍍鉻星環-7.png",
    "紫色殘月星環-10.png",
];

// 中文檔名放進網址前先編碼，避免某些伺服器讀不到
function moonImg($phase)
{
    global $phaseFiles;
    return "images/month2/" . rawurlencode($phaseFiles[$phase]);
}

$selectedPhase = getMoonPhase($year, $month, $selected);
?>
<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>月汐紀 - 月相萬年曆</title>

    <!-- 字體：英文 Cormorant Garamond、中文 Noto Serif TC -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Noto+Serif+TC:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- 萬年曆樣式 -->
    <link rel="stylesheet" href="style_dark.css?v=<?= filemtime(__DIR__ . '/style_dark.css') ?>">

    <!-- Cookie 寵物樣式（和第一版共用同一份） -->
    <link rel="stylesheet" href="cookie_pet.css?v=<?= filemtime(__DIR__ . '/cookie_pet.css') ?>">
</head>

<body>

    <div class="page">

        <!-- ===== 頁首 ===== -->
        <header class="top">
            <div class="brand">
                <span class="star">✦</span>
                <span class="brand-en">LUNAR TIDES</span>
                <span class="brand-sep"></span>
                <span class="brand-zh">月汐紀</span>
            </div>

            <nav class="menu">
                <a class="active" href="calendar_dark.php">月相萬年曆</a>
                <a href="calendar_new.php">紫月軌道</a>
                <a href="index.html">回首頁</a>
            </nav>
        </header>

        <div class="layout">

            <!-- ===== 左邊：萬年曆 ===== -->
            <main class="main">

                <div class="toolbar">
                    <div class="title">
                        <span class="ym"><?= $year ?> / <?= $month ?></span>
                        <span class="title-sep"></span>
                        <span class="en"><?= $monthNames[$month] ?></span>
                    </div>

                    <div class="controls">
                        <?php if ($hasPrev): ?>
                            <a class="btn icon" href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>" title="上一個月">&#10094;</a>
                        <?php else: ?>
                            <span class="btn icon disabled">&#10094;</span>
                        <?php endif; ?>

                        <!-- 選年份 / 月份後自動送出 -->
                        <form method="get">
                            <select name="year" onchange="this.form.submit()">
                                <?php for ($y = 1970; $y <= 2100; $y++): ?>
                                    <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?> 年</option>
                                <?php endfor; ?>
                            </select>
                            <select name="month" onchange="this.form.submit()">
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>><?= $m ?> 月</option>
                                <?php endfor; ?>
                            </select>
                        </form>

                        <a class="btn today-btn" href="?year=<?= date('Y') ?>&month=<?= date('n') ?>">今天</a>

                        <?php if ($hasNext): ?>
                            <a class="btn icon" href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>" title="下一個月">&#10095;</a>
                        <?php else: ?>
                            <span class="btn icon disabled">&#10095;</span>
                        <?php endif; ?>
                    </div>
                </div>

                <table class="calendar">
                    <thead>
                        <tr>
                            <?php foreach ($names as $name): ?>
                                <th><?= $name ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php for ($i = 0; $i < $weeks; $i++): ?>
                            <tr>
                                <?php for ($j = 0; $j < 7; $j++): ?>
                                    <?php $day = $i * 7 + $j - $firstWeekday + 1; ?>

                                    <?php if ($day >= 1 && $day <= $days): ?>
                                        <?php
                                        $phase = getMoonPhase($year, $month, $day);
                                        $classes = [];
                                        if ($isThisMonth && $day == $todayDay) $classes[] = 'today';
                                        if ($day == $selected) $classes[] = 'selected';
                                        ?>
                                        <td class="<?= implode(' ', $classes) ?>">
                                            <!-- 點日期：右邊顯示這一天的月相 -->
                                            <a href="?year=<?= $year ?>&month=<?= $month ?>&day=<?= $day ?>" title="<?= $phaseNames[$phase] ?>">
                                                <span class="day-number"><?= $day ?></span>
                                                <img src="<?= moonImg($phase) ?>" alt="<?= $phaseNames[$phase] ?>" loading="lazy">
                                            </a>
                                        </td>
                                    <?php else: ?>
                                        <td class="empty"></td>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>

                <!-- 月相圖例 -->
                <section class="legend" id="phases">
                    <?php foreach ($phaseNames as $p => $pname): ?>
                        <div class="legend-item">
                            <img src="<?= moonImg($p) ?>" alt="<?= $pname ?>" loading="lazy">
                            <span><?= $pname ?></span>
                        </div>
                    <?php endforeach; ?>
                </section>

                <p class="note">☾ 月相為近似模擬，非精密天文預報 ☽</p>
            </main>

            <!-- ===== 右邊：所選日期 ===== -->
            <aside class="side">
                <p class="side-label"><span>✦</span> 所選日期 <span>✦</span></p>
                <p class="side-date"><?= $year ?>.<?= $month ?>.<?= $selected ?></p>

                <img class="side-moon" src="<?= moonImg($selectedPhase) ?>" alt="<?= $phaseNames[$selectedPhase] ?>">

                <p class="side-phase"><?= $phaseNames[$selectedPhase] ?></p>
                <p class="side-pill">月相示意</p>

                <?php if ($isThisMonth && $selected == $todayDay): ?>
                    <p class="side-today">— 今天 —</p>
                <?php endif; ?>
            </aside>

        </div>
    </div>

    <!-- Cookie 網頁寵物（和第一版一樣） -->
    <div id="cookie-pet">
        <div class="cookie-speech" hidden></div>
        <img id="cookie-image" src="images/cookie/cookie_blink.gif" alt="巧克力色長毛吉娃娃 Cookie">
    </div>
    <script src="cookie_pet.js?v=<?= filemtime(__DIR__ . '/cookie_pet.js') ?>"></script>

</body>

</html>
