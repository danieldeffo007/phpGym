<?php
session_start();
    if ($_SESSION['user']!=='admin'){
        header('Location: admin_signin.php');
    }
    if(isset($_GET['view'])) {
        if($_GET['view'] === 'logout'){
            unset($_SESSION['user']);
            header('Location: admin_signin.php');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="">
    <title>Админ Панель</title>
</head>
<body>
    <h1>Админ Панель</h1>
    <div>
        <a href="clients.php">Клиенты (регистрация)</a><br>
        <a href="machines.php">Описание тренажеров</a><br>
        <a href="repair.php">Заявки на ремонт тренажеров</a><br>
        <a href="trainers.php">Описание тренеров</a><br>
        <a href="subscribe.php">Запись к тренерам</a><br>
        <a href="suggestions.php">Предложения и пожелания</a><br>
        <br><br><br>
        <a href="admin_panel.php?view=logout">Выход</a>
    </div>

</body>
</html>