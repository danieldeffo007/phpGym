<?php
session_start();
require_once 'database.php';
if(isset($_GET['view'])) {
    if($_GET['view']==='add'){
        $fio = $_POST['fio'];
        $number_phone = $_POST['number_phone'];
        $birth_date = $_POST['birth_date'];
        $login = $_POST['login'];
        $password = $_POST['password'];
        $query = "INSERT INTO clients (fio, number_phone, birth_date, login, password)
VALUES ('$fio', '$number_phone', '$birth_date', '$login', '$password');";
        $result = pg_query($dbconn, $query);
        header('Location: clients.php');
    }
    elseif ($_GET['view']==='update'){
        $_SESSION['idClient']= $_POST['id'];
        header('Location: clients.php');
    }
    elseif ($_GET['view']==='delete'){
        $idClient = $_POST['id'];
        $query = "DELETE FROM clients WHERE id=$idClient;";
        $result = pg_query($dbconn,$query);
        header('Location: clients.php');
    }
    elseif ($_GET['view']==='save_update'){
        $idClient = $_POST['id'];
        $fio = $_POST['fio'];
        $number_phone = $_POST['number_phone'];
        $birth_date = $_POST['birth_date'];
        $login = $_POST['login'];
        $password = $_POST['password'];
        $query = "UPDATE clients
SET fio = '$fio', number_phone = '$number_phone', birth_date = '$birth_date', login = '$login', password = '$password'
WHERE id = '$idClient';";
        $result = pg_query($dbconn,$query);
        unset($_SESSION['idClient']);
        header('Location: clients.php');
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="">
    <title>Клиенты</title>
</head>
<body>
<a href="admin_panel.php">На главную</a>
    <h1>Клиенты</h1>
    <div>
        <h2>Добавить</h2>
        <form method="POST" action="clients.php?view=add">
            <label for="fio">ФИО:</label>
            <input type="text" name="fio" id="fio" required><br><br>

            <label for="number_phone">Телефон:</label>
            <input type="text" name="number_phone" id="number_phone" required><br><br>

            <label for="birth_date">Дата рождения:</label>
            <input type="text" name="birth_date" id="birth_date" required><br><br>

            <label for="login">Логин:</label>
            <input type="text" name="login" id="login" required><br><br>

            <label for="password">Пароль:</label>
            <input type="text" name="password" id="password" required><br><br>

            <input type="submit" value="Сохранить">
        </form>
    </div>
    <div>
        <h2>Список клиентов</h2>
        <?php
        $query = "SELECT * FROM clients";
        $result = pg_query($dbconn, $query);
        while ($row = pg_fetch_assoc($result)) {
            echo "
            <pre>ФИО: ".$row['fio'].",Номер телефона: ".$row['number_phone'].", Дата рождения: ".$row['birth_date'].", Логин: ".$row['login'].", Пароль: ".$row['password']."</pre>
            <form action='clients.php?view=update' method='POST'>
                <button type='submit' name='id' value='".$row['id']."'>Редактировать</button>
            </form>
            <form action='clients.php?view=delete' method='POST'>
                <button name='id' type='submit' value='".$row['id']."' >Удалить</button>
            </form>
            ";
            if(isset($_SESSION['idClient'])){
                if($_SESSION['idClient']===$row['id']){

                    echo "
            <form method='POST' action='clients.php?view=save_update'>
            <input style='display: none' value='".$row['id']."' name='id'>
            <label for='fio' >ФИО:</label>
            <input type='text' name='fio' id='fio' value='".$row['fio']."' required><br><br>

            <label for='number_phone'>Телефон:</label>
            <input type='text' name='number_phone' id='number_phone' value='".$row['number_phone']."' required><br><br>

            <label for='birth_date'>Дата рождения:</label>
            <input type='text' name='birth_date' id='birth_date' value='".$row['birth_date']."' required><br><br>

            <label for='login'>Логин:</label>
            <input type='text' name='login' id='login' value='".$row['login']."' required><br><br>

            <label for='password'>Пароль:</label>
            <input type='text' name='password' id='password' value='".$row['password']."' required><br><br>

            <input type='submit' value='Сохранить'>
        </form>
                    ";
                }
            }
        }
        ?>
    </div>
    <br><br><br>
    <a href="admin_panel.php?view=logout">Выход</a>
</body>
</html>