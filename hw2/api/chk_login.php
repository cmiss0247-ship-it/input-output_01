<?php
// 預設的帳號密碼
$users = [
    'Admin' => '0857',
];

$acc = trim($_POST['acc'] ?? '');
$pw  = $_POST['pw'] ?? '';

if ($acc !== '' && isset($users[$acc]) && $users[$acc] === $pw) {
    // 登入成功：帶著帳號到會員中心
    header('Location: ../user.php?acc=' . urlencode($acc));
} else {
    // 登入失敗：導到錯誤頁
    header('Location: ../error.php');
}
exit;
