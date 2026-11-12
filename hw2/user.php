<?php
// 沒有帶帳號參數就導回登入頁
if (empty($_GET['acc'])) {
    header('Location: login.html');
    exit;
}

$user = htmlspecialchars($_GET['acc']);
$loginTime = date('Y-m-d H:i:s');
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>會員中心</title>
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
            font-family: "Franklin Gothic", "Arial Black", "Microsoft JhengHei", sans-serif;
            font-weight: bold;
            background: var(--bg);
            color: var(--text);
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 28px;
            border-bottom: 1px solid var(--grid);
        }
        .brand { font-size: 22px; letter-spacing: 4px; color: var(--today); }
        .logout {
            padding: 8px 18px;
            color: var(--bg);
            text-decoration: none;
            font-size: 14px;
            background: var(--today);
            border: 2px solid var(--today);
            transition: background .15s, color .15s;
        }
        .logout:hover { background: transparent; color: var(--today); }
        main { max-width: 760px; margin: 40px auto; padding: 0 20px; }
        .toast {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            margin-bottom: 24px;
            border: 1px solid var(--today);
            border-left: 6px solid var(--today);
            color: var(--today);
            animation: slideIn .5s ease, fadeOut .6s ease 4s forwards;
        }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-12px); } to { opacity: 1; transform: none; } }
        @keyframes fadeOut { to { opacity: 0; height: 0; padding: 0 18px; margin: 0; border-width: 0; } }
        .card {
            border: 1px solid var(--grid);
            padding: 36px;
            text-align: center;
        }
        .avatar {
            width: 88px;
            height: 88px;
            margin: 0 auto 16px;
            background: var(--weekend);
            color: var(--text);
            font-size: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        h1 { margin: 0 0 6px; font-size: 30px; letter-spacing: 2px; }
        .sub { margin: 0 0 28px; color: var(--muted); font-size: 14px; }
        .info { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; text-align: left; }
        .info div { padding: 16px; border: 1px solid var(--grid); }
        .info span { display: block; font-size: 13px; color: var(--muted); margin-bottom: 4px; }
        .info strong { font-size: 16px; }
        .status { color: var(--today); }
    </style>
</head>
<body>
    <header>
        <div class="brand">會員中心</div>
        <a class="logout" href="login.html">登出</a>
    </header>

    <main>
        <div class="toast">✔ 登入成功！歡迎回來，<?= $user ?></div>

        <div class="card">
            <div class="avatar"><?= strtoupper(mb_substr($user, 0, 1)) ?></div>
            <h1>Hi, <?= $user ?></h1>
            <p class="sub">這是您的會員中心</p>
            <div class="info">
                <div><span>帳號</span><strong><?= $user ?></strong></div>
                <div><span>登入時間</span><strong><?= $loginTime ?></strong></div>
                <div><span>會員狀態</span><strong class="status">● 已登入</strong></div>
            </div>
        </div>
    </main>
</body>
</html>
