<?php
session_start();
require_once 'database.php';
if(isset($_GET['view'])) {
    if($_GET['view']==='change'){
        $status = $_POST['status'];
        $idRepair = $_POST['id'];
        if($status==="На проверке") $status = "В работе";
        elseif($status==="В работе")$status = "Готово";
        $query = "UPDATE repair
SET status = '$status'
WHERE id = '$idRepair';";
        $result = pg_query($dbconn, $query);
        header('Location: repair.php');
    }

    elseif ($_GET['view']==='delete'){
        $idRepair = $_POST['id'];
        $query = "DELETE FROM repair WHERE id=$idRepair;";
        $result = pg_query($dbconn,$query);
        header('Location: repair.php');
    }


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="CSS/repair.css">
    <title>Ремонт тренажеров</title>
    <style>
        .faq-question {
            font-weight: bold;
        }

        .faq-answer {
            display: none;
            margin-left: 15px;
        }

        .faq-details {
            margin-bottom: 10px;
        }

        .faq-details[open] .faq-answer {
            display: block;
        }
    </style>
</head>
<body>
<a href="admin_panel.php">На главную</a>
<h1>Ремонт тренажеров</h1>
<div>
    <h2>На проверке</h2>
    <?php
    $query = "SELECT * FROM repair WHERE status='На проверке'";
    $result = pg_query($dbconn, $query);
    while ($row = pg_fetch_assoc($result)) {
        echo "
            <div class='block faq'>
                <details class='faq-details'>
                <summary class='faq-question'>#".$row['id']."</summary>
                <div class='faq-answer'>
                <p>Описание тренажера: ".$row['view']."</p> <p>Дефект: ".$row['defect']."</p> <p>Дата заявки: ".$row['date_creation']."</p>
            
            <form action='repair.php?view=change' method='POST'>
                <input style='display: none' value='".$row['status']."' name='status'>
                <button type='submit' name='id' value='".$row['id']."'>Сменить статус</button>
            </form>
            <form action='repair.php?view=delete' method='POST'>
                <button name='id' type='submit' value='".$row['id']."' >Удалить</button>
            </form>
            </div>
            </div>
            ";
    }
    ?>
</div>
<div>
    <h2>В работе</h2>
    <?php
    $query = "SELECT * FROM repair WHERE status='В работе'";
    $result = pg_query($dbconn, $query);
    while ($row = pg_fetch_assoc($result)) {
        echo "
            <div class='block faq'>
                <details class='faq-details'>
                <summary class='faq-question'>#".$row['id']."</summary>
                <div class='faq-answer'>
                <p>Описание тренажера: ".$row['view']."</p> <p>Дефект: ".$row['defect']."</p> <p>Дата заявки: ".$row['date_creation']."</p>
            <form action='repair.php?view=change' method='POST'>
                <input style='display: none' value='".$row['status']."' name='status'>
                <button type='submit' name='id' value='".$row['id']."'>Сменить статус</button>
            </form>
            <form action='repair.php?view=delete' method='POST'>
                <button name='id' type='submit' value='".$row['id']."' >Удалить</button>
            </form>
            </div>
            </div>
            ";
    }
    ?>
</div>
<div>
    <h2>Готово</h2>
    <?php
    $query = "SELECT * FROM repair WHERE status='Готово'";
    $result = pg_query($dbconn, $query);
    while ($row = pg_fetch_assoc($result)) {
        echo "
            <div class='block faq'>
                <details class='faq-details'>
                <summary class='faq-question'>#".$row['id']."</summary>
                <div class='faq-answer'>
                <p>Описание тренажера: ".$row['view']."</p> <p>Дефект: ".$row['defect']."</p> <p>Дата заявки: ".$row['date_creation']."</p>
            <form action='repair.php?view=delete' method='POST'>
                <button name='id' type='submit' value='".$row['id']."' >Удалить</button>
            </form>
            </div>
            </div>
            ";
    }
    ?>
</div>
<br><br><br>
<a href="admin_panel.php?view=logout">Выход</a>
</body>
</html>