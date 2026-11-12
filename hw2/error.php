<?php
$seconds = 10;   // 自動導回登入頁的秒數
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 不靠 JavaScript 也能在指定秒數後自動導回登入頁 -->
    <meta http-equiv="refresh" content="<?= $seconds ?>;url=login.html">
    <title>登入失敗</title>
    <style>
        /* 與 calendar_new.php 相同的色彩搭配 */
        :root {
            --bg: #0B0B0E;
            --text: #EDEBE3;
            --grid: #3A3A44;
            --today: #C8FF2E;
            --holiday: #FF2E7E;
            --weekend: #7A4DFF;
            --muted: #9A98A0;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: "Franklin Gothic", "Arial Black", "Microsoft JhengHei", sans-serif;
            font-weight: bold;
            background: var(--bg);
            color: var(--text);
        }
        .card {
            width: 100%;
            max-width: 400px;
            border: 1px solid var(--grid);
            border-top: 6px solid var(--holiday);
            padding: 40px 36px;
            text-align: center;
        }
        .icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 18px;
            background: var(--holiday);
            color: var(--bg);
            font-size: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: shake .5s ease;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-8px); }
            40%, 80% { transform: translateX(8px); }
        }
        h1 { margin: 0 0 8px; font-size: 30px; letter-spacing: 4px; color: var(--holiday); }
        .sub { margin: 0 0 26px; color: var(--muted); font-size: 15px; line-height: 1.6; }
        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            font-size: 17px;
            color: var(--bg);
            text-decoration: none;
            background: var(--today);
            border: 2px solid var(--today);
            transition: background .15s, color .15s;
        }
        .btn:hover { background: transparent; color: var(--today); }
        .countdown { margin-top: 20px; font-size: 14px; color: var(--muted); }
        .countdown strong { color: var(--holiday); font-size: 18px; }
        .bar { margin-top: 10px; height: 6px; background: var(--grid); overflow: hidden; }
        .bar div {
            height: 100%;
            background: var(--weekend);
            animation: shrink <?= $seconds ?>s linear forwards;
        }
        @keyframes shrink { from { width: 100%; } to { width: 0; } }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">!</div>
        <h1>登入失敗</h1>
        <p class="sub">登月失敗，請確認地球人身分。</p>

        <a class="btn" href="login.html">回登入頁</a>

        <p class="countdown"><strong id="sec"><?= $seconds ?></strong> 秒後將自動返回登入頁</p>
        <div class="bar"><div></div></div>
    </div>

    <script>
        // 倒數秒數顯示（實際導頁由上方的 meta refresh 負責）
        let sec = <?= $seconds ?>;
        const el = document.getElementById('sec');
        setInterval(() => {
            if (sec > 0) el.textContent = --sec;
        }, 1000);
    </script>
</body>
</html>
