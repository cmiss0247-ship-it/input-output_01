<?php
$year = (int)($_GET['year'] ?? date('Y'));
$month = (int)($_GET['month'] ?? date('n'));
if ($month < 1 || $month > 12) $month = (int)date('n');
if ($year < 1 || $year > 9999) $year = (int)date('Y');

$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevYear--;
}

$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
}

$daysInMonth = (int)date('t', mktime(0, 0, 0, $month, 1, $year));
$firstWeekday = (int)date('w', mktime(0, 0, 0, $month, 1, $year));

$weekNames = ['日', '一', '二', '三', '四', '五', '六'];
$today = date('Y-n-j');

// 月份圖片：images/month/C_01.png ~ C_12.png
$monthImage = sprintf('images/month/C_%02d.png', $month);

$holidays = [
    '1-1' => '元旦',
    '2-28' => '和平紀念日',
    '3-8' => '婦女節',
    '3-12' => '植樹節',
    '3-14' => 'Claude 上線紀念 / 白色情人節',
    '4-4' => '兒童節',
    '4-22' => '世界地球日',
    '5-1' => '勞動節',
    '8-8' => '父親節',
    '9-28' => '教師節',
    '10-10' => '國慶日',
    '10-25' => '臺灣光復節',
    '10-31' => '萬聖節',
    '11-11' => '雙十一購物節',
    '11-12' => '國父誕辰',
    '11-30' => 'ChatGPT 上線紀念',
    '12-25' => '行憲紀念日 / 聖誕節',
];
// 母親節：5 月第二個星期日
$motherDay = date('n-j', strtotime("second sunday of May $year"));
$holidays[$motherDay] = '母親節';

?>
<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>萬年曆 - 紫月軌道</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0B0B0E;
            --text: #EDEBE3;
            --line: #3A3A44;
            --line-soft: #2A2A32;
            --purple: #7A4DFF;
            --lavender: #C9B3FF;
            --pink: #FF2E7E;
            --lime: #C8FF2E;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: "Outfit", "Microsoft JhengHei", sans-serif;
        }

        .page {
            max-width: 1440px;
            margin: 0 auto;
            padding: 32px 56px 24px;
            display: flex;
            flex-direction: column;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* ===== 頁首 ===== */
        .brand {
            display: flex;
            align-items: center;
            gap: 18px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--line);
        }

        .brand-en {
            font-size: 44px;
            font-weight: 900;
            letter-spacing: 1px;
        }

        .slash {
            color: var(--pink);
            font-size: 36px;
            font-weight: 300;
        }

        .brand-zh {
            font-size: 24px;
            letter-spacing: 6px;
        }

        /* 右上角：回首頁 */
        .home-link {
            margin-left: auto;
            font-size: 16px;
            letter-spacing: 3px;
            color: #8A8892;
            white-space: nowrap;
        }

        .home-link:hover {
            color: var(--pink);
        }

        /* ===== 主要區塊 ===== */
        .main {
            flex: 1;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 40px;
            padding: 36px 0 28px;
            align-items: start;
        }

        .cover {
            border: 1px solid var(--line);
            aspect-ratio: 1 / 1;
            overflow: hidden;
        }

        .cover img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* 大月份 | 年份 */
        .title {
            display: flex;
            align-items: center;
            gap: 32px;
            margin-bottom: 28px;
        }

        .big-month {
            font-size: 160px;
            font-weight: 900;
            line-height: 0.85;
            letter-spacing: -4px;
        }

        .big-year {
            font-size: 72px;
            font-weight: 800;
            padding-left: 32px;
            border-left: 1px solid var(--line);
            line-height: 1.8;
        }

        /* ===== 控制列 ===== */
        .controls {
            display: flex;
            align-items: center;
            margin-bottom: 36px;
        }

        .controls form {
            display: flex;
            align-items: center;
        }

        .arrow {
            font-size: 28px;
            padding: 0 20px;
            line-height: 1;
        }

        .arrow:first-child {
            padding-left: 10px;
        }

        .arrow:hover,
        .today-btn:hover {
            color: var(--lime);
        }

        .sep {
            width: 1px;
            height: 40px;
            background: var(--line);
        }

        .controls select {
            appearance: none;
            -webkit-appearance: none;
            background: transparent url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='8' viewBox='0 0 14 8'%3E%3Cpath d='M1 1l6 6 6-6' fill='none' stroke='%23EDEBE3' stroke-width='1.6'/%3E%3C/svg%3E") no-repeat right 0 center;
            color: var(--text);
            border: none;
            font: 600 28px "Outfit", sans-serif;
            padding: 0 26px 0 0;
            margin: 0 44px;
            cursor: pointer;
        }

        .controls select option {
            background: var(--bg);
            font-size: 16px;
        }

        .today-btn {
            margin-left: auto;
            border: 1px solid var(--text);
            padding: 8px 32px;
            font-size: 18px;
            letter-spacing: 4px;
        }

        /* ===== 日曆表格 ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid var(--line);
            text-align: center;
        }

        tr> :first-child {
            border-left: none;
        }

        tr> :last-child {
            border-right: none;
        }

        th {
            height: 64px;
            font-size: 24px;
            font-weight: 400;
        }

        td {
            height: 96px;
            padding: 8px 4px 6px;
            vertical-align: top;
            font-size: 32px;
            font-weight: 600;
            position: relative;
        }

        .weekend {
            color: var(--lavender);
        }

        .today {
            background: var(--purple);
            color: var(--text);
        }

        /* 節日：數字下方的文字，太長會自動換行（最多兩行） */
        td small {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            overflow: hidden;
            margin-top: 6px;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.3;
            color: var(--pink);
            word-break: break-all;
        }

        .today small {
            color: var(--text);
        }

        /* ===== 頁尾 ===== */
        .footer {
            display: flex;
            align-items: center;
            border-top: 1px solid var(--line);
            padding: 18px 0 20px;
            font-size: 15px;
            letter-spacing: 5px;
            gap: 20px;
        }

        .footer .slash {
            font-size: 18px;
        }

        .footer .slash:last-child {
            margin-left: auto;
        }

        /* ===== 平板 / 手機 ===== */
        @media (max-width: 1100px) {
            .main {
                grid-template-columns: 1fr;
            }

            .cover {
                max-width: 560px;
                width: 100%;
                margin: 0 auto;
            }
        }

        @media (max-width: 650px) {
            .page {
                padding: 20px 16px;
            }

            .brand-en {
                font-size: 28px;
            }

            .brand-zh {
                font-size: 16px;
                letter-spacing: 3px;
            }

            .big-month {
                font-size: 96px;
            }

            .big-year {
                font-size: 40px;
                padding-left: 20px;
            }

            .controls select {
                font-size: 20px;
                margin: 0 14px;
            }

            .arrow {
                padding: 0 10px;
            }

            .today-btn {
                padding: 6px 12px;
                font-size: 14px;
                letter-spacing: 2px;
            }

            th {
                height: 44px;
                font-size: 16px;
            }

            td {
                height: 72px;
                padding: 6px 2px 4px;
                font-size: 20px;
            }

            td small {
                margin-top: 4px;
                font-size: 11px;
            }
        }
    </style>
    <!-- 加上 ?v=檔案修改時間，避免瀏覽器一直用舊的快取檔 -->
    <link rel="stylesheet" href="cookie_pet.css?v=<?= filemtime(__DIR__ . '/cookie_pet.css') ?>">
</head>

<body>
    <div id="cookie-pet">
        <div class="cookie-speech" hidden></div>
        <img id="cookie-image" src="images/cookie/cookie_blink.gif" alt="餅乾">
    </div>

    <div class="page">
        <header class="brand">
            <span class="brand-en">LUNAR ORBIT</span>
            <span class="slash">/</span>
            <span class="brand-zh">紫月軌道</span>
            <a class="home-link" href="index.html">&#10094; 回首頁</a>
        </header>

        <main class="main">
            <!-- 左邊：月份圖片 -->
            <div class="cover">
                <img src="<?= $monthImage ?>" alt="<?= $month ?>月">
            </div>

            <!-- 右邊：日曆 -->
            <section>
                <div class="title">
                    <div class="big-month"><?= $month ?></div>
                    <div class="big-year"><?= $year ?></div>
                </div>

                <div class="controls">
                    <a class="arrow" href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>" title="上一月">&#10094;</a>
                    <span class="sep"></span>

                    <!-- 選擇年份 / 月份後自動送出 -->
                    <form method="get">
                        <select name="year" onchange="this.form.submit()">
                            <?php for ($y = max(1, $year - 10); $y <= min(9999, $year + 10); $y++): ?>
                                <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                        <span class="sep"></span>
                        <select name="month" onchange="this.form.submit()">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>><?= $m ?></option>
                            <?php endfor; ?>
                        </select>
                    </form>

                    <span class="sep"></span>
                    <a class="arrow" href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>" title="下一月">&#10095;</a>

                    <a class="today-btn" href="?year=<?= date('Y') ?>&month=<?= date('n') ?>">今天</a>
                </div>

                <table>
                    <tr>
                        <?php foreach ($weekNames as $i => $w): ?>
                            <th class="<?= ($i == 0 || $i == 6) ? 'weekend' : '' ?>"><?= $w ?></th>
                        <?php endforeach; ?>
                    </tr>

                    <?php
                    $cell = 0;
                    echo "<tr>";
                    for ($i = 0; $i < $firstWeekday; $i++) {
                        echo "<td></td>";
                        $cell++;
                    }
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $mdKey = $month . "-" . $day;
                        $holidayName = $holidays[$mdKey] ?? '';
                        $class = '';
                        $weekday = $cell % 7;
                        if ($weekday == 0 || $weekday == 6) $class .= 'weekend ';
                        if ("$year-$month-$day" == $today) $class .= 'today ';
                        if ($holidayName != '') $class .= 'holiday ';
                        $note = $holidayName != '' ? "<small title='$holidayName'>$holidayName</small>" : '';
                        echo "<td class='$class'>$day$note</td>";
                        $cell++;
                        if ($cell % 7 == 0 && $day < $daysInMonth) echo "</tr><tr>";
                    }

                    while ($cell % 7 != 0) {
                        echo "<td></td>";
                        $cell++;
                    }
                    echo "</tr>";
                    ?>
                </table>
            </section>
        </main>

        <footer class="footer">
            <span>LUNAR ORBIT</span>
            <span class="slash">/</span>
            <span>紫月軌道</span>
            <span class="slash">/</span>
        </footer>
    </div>

    <script src="cookie_pet.js?v=<?= filemtime(__DIR__ . '/cookie_pet.js') ?>"></script>
</body>

</html>
