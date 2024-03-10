<?php
session_start();
require_once 'database.php';
if(isset($_GET['view'])) {
    if($_GET['view']==='add'){
        $fio = $_POST['fio'];
        $description = $_POST['description'];
        if (isset($_FILES['image'])){
            $image = $_FILES['image'];
            $uploadDir = 'photo_trainers/';
            $imageName = uniqid() . '_' . $image['fio'];
            $imagePath = $uploadDir . $imageName;
            if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                $query = "INSERT INTO trainers (fio, photo, description) VALUES ($1, $2, $3)";
                $params = [$fio, $imagePath, $description];
                $result = pg_query_params($dbconn, $query, $params);
            }
        }
        header('Location: trainers.php');
    }
    elseif ($_GET['view']==='update'){
        $_SESSION['idTrainer']= $_POST['id'];
        header('Location: trainers.php');
    }
    elseif ($_GET['view']==='delete'){
        $idTrainer = $_POST['id'];
        $query = "DELETE FROM trainers WHERE id=$idTrainer;";
        $result = pg_query($dbconn,$query);
        $photo_path = $_POST['photo_path'];
        unlink($photo_path);
        header('Location: trainers.php');
    }
    elseif ($_GET['view'] === 'save_update') {
        $idTrainer = $_POST['id'];
        $fio = $_POST['fio'];
        $description = $_POST['description'];
        $photo_path = $_POST['photo_path'];
        unlink($photo_path);
        if (isset($_FILES['image2'])) {
            $image2 = $_FILES['image2'];
            $uploadDir = 'photo_trainers/';
            $imageName = uniqid() . '_' . $image2['fio'];
            $imagePath = $uploadDir . $imageName;
            if (move_uploaded_file($image2['tmp_name'], $imagePath)) {
                $query = "UPDATE trainers
SET fio = '$fio', photo = '$imagePath', description = '$description'
WHERE id = '$idTrainer';";
                $result = pg_query($dbconn, $query);
            }
        }
        unset($_SESSION['idTrainer']);
        header('Location: trainers.php');
    }
    elseif ($_GET['view']==='cancel'){
        unset($_SESSION['idTrainer']);
        header('Location: trainers.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/CSS/trainers.css">
    <title>Описание тренеров</title>
</head>
<body>
<a href="admin_panel.php">На главную</a>
<h1>Описание тренеров</h1>

<div>
    <h2>Добавить</h2>
    <form method="POST" action="trainers.php?view=add" enctype="multipart/form-data">
        <label for="fio">ФИО:</label>
        <input type="text" name="fio" id="fio" required><br><br>

        <label for="image">Фото:</label>
        <input type="file" name="image" id="image" required><br><br>

        <label for="description">Описание:</label>
        <textarea type="text" name="description" id="description" required></textarea><br><br>

        <input type="submit" value="Сохранить">
    </form>
</div>
<div>
    <h2>Список тренеров</h2>
    <?php
    $query = "SELECT * FROM trainers";
    $result = pg_query($dbconn, $query);
    while ($row = pg_fetch_assoc($result)) {
        $image = $row['photo'];
        echo "
            <pre>ФИО: ".$row['fio'].", Описание: ".$row['description']."</pre>";
        echo '<img class="img_trainers" src="' . $row['photo'] . '" alt="'.$row['fio'].'">';
        echo "
            <form class='form_update' action='trainers.php?view=update' method='POST'>
                <button type='submit' name='id' value='".$row['id']."'>Редактировать</button>
            </form>
            <form class='form_update' action='trainers.php?view=delete' method='POST'>
                <button name='id' type='submit' value='".$row['id']."' >Удалить</button><br><br>
            </form>
            ";
        if(isset($_SESSION['idTrainer'])){
            if($_SESSION['idTrainer']===$row['id']){

                echo "
            <form method='POST' action='trainers.php?view=save_update' enctype='multipart/form-data'>
                <input style='display: none' value='".$row['id']."' name='id'>
                <input style='display: none' value='".$row['photo']."' name='photo_path'>
                <label for='fio' >Название:</label>
                <input type='text' name='fio' id='fio' value='".$row['fio']."' required><br><br>
            
                <label for='image2'>Фото:</label>
                <input type='file' name='image2' id='image2' required><br><br>
            
                <label for='description'>Описание:</label>
                <textarea type='text' name='description' id='description' required >".$row['description']."</textarea><br><br>

                <input type='submit' value='Сохранить'>
            </form>
             
            <form action='trainers.php?view=cancel' method='POST'>
                <input style='display: none' value='".$row['photo']."' name='photo_path'>
                <button name='id' type='submit' value='".$row['id']."' >Отменить</button>
            </form>       ";
            }
        }

    }
    ?>
</div>
<br><br><br>
<a href="admin_panel.php?view=logout">Выход</a>
</body>
</html>