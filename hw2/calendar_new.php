<?php
$year = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? date('n');
$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevMonth--;
}

$nextMonth = $month + 1;
$nextMonth = $year;
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
}

$dayInMonth = date('t', mktime(0, 0, 0, $month, 1, $year));
$firstWeekday = date('w', mktime(0, 0, 0, $month, 1, $year));

$weekName = ['日', '一', '二', '三', '四', '五', '六'];
$today = date('Y-n-j');


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>

<body>
    <h2><?= $year ?>年<?= $month ?></h2>
    <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>">上一月</a>
    <a href="?year=<?= $nextYear ?>&month=<? $nextMonth ?>">下一月</a>
    <table>
        <tr>
          <?php foreach ($weekNames as $i => $w): ?>
            <th><?= $w ?></th>
            <?php endforeach; ?>
        </tr>

        <?php 
        $cell=0;
        echo "<tr>";
        for ($i=0; $i< $firstWeekday; $i++){ echo "<td></td>"; $cell++;}
        for ($day=1; $day<= $daysInMonth; $day++){
            echo "<td>"}
        
        
        
        ?>

        <img src="images\cookie_blink.gif" alt="餅乾" width="120" style="display: block; margin: auto;">
    </table>

</body>

</html>