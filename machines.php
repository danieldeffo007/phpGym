<?php
session_start();
require_once 'database.php';
if(isset($_GET['view'])) {
    if($_GET['view']==='add'){
        $name = $_POST['name'];
        $description = $_POST['description'];
        if (isset($_FILES['image'])){
            $image = $_FILES['image'];
            $uploadDir = 'photo_machines/';
            $imageName = uniqid() . '_' . $image['name'];
            $imagePath = $uploadDir . $imageName;
            if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                $query = "INSERT INTO machines (name, photo, description) VALUES ($1, $2, $3)";
                $params = [$name, $imagePath, $description];
                $result = pg_query_params($dbconn, $query, $params);
            }
        }
        header('Location: machines.php');
    }
    elseif ($_GET['view']==='update'){
        $_SESSION['idMachine']= $_POST['id'];
        header('Location: machines.php');
    }
    elseif ($_GET['view']==='delete'){
        $idMachine = $_POST['id'];
        $query = "DELETE FROM machines WHERE id=$idMachine;";
        $result = pg_query($dbconn,$query);
        $photo_path = $_POST['photo_path'];
        unlink($photo_path);
        header('Location: machines.php');
    }
    elseif ($_GET['view'] === 'save_update') {
        $idMachine = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $photo_path = $_POST['photo_path'];
        unlink($photo_path);
        if (isset($_FILES['image2'])) {
            $image2 = $_FILES['image2'];
            $uploadDir = 'photo_machines/';
            $imageName = uniqid() . '_' . $image2['name'];
            $imagePath = $uploadDir . $imageName;
            if (move_uploaded_file($image2['tmp_name'], $imagePath)) {
                $query = "UPDATE machines
SET name = '$name', photo = '$imagePath', description = '$description'
WHERE id = '$idMachine';";
                $result = pg_query($dbconn, $query);
            }
        }
        unset($_SESSION['idMachine']);
        header('Location: machines.php');
    }
    elseif ($_GET['view']==='cancel'){
        unset($_SESSION['idMachine']);
        header('Location: machines.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/CSS/machines.css">
    <title>Описание тренажеров</title>
</head>
<body>
<a href="admin_panel.php">На главную</a>
<h1>Описание тренажеров</h1>

<div>
    <h2>Добавить</h2>
    <form method="POST" action="machines.php?view=add" enctype="multipart/form-data">
        <label for="name">Название:</label>
        <input type="text" name="name" id="name" required><br><br>

        <label for="image">Фото:</label>
        <input type="file" name="image" id="image" required><br><br>

        <label for="description">Описание:</label>
        <textarea type="text" name="description" id="description" required></textarea><br><br>

        <input type="submit" value="Сохранить">
    </form>
</div>
<div>
    <h2>Список тренажеров</h2>
    <?php
    $query = "SELECT * FROM machines";
    $result = pg_query($dbconn, $query);
    while ($row = pg_fetch_assoc($result)) {
        $image = $row['photo'];
        echo "
            <pre>Название: ".$row['name'].", Описание: ".$row['description']."</pre>";
        echo '<img class="img_machines" src="' . $row['photo'] . '" alt="'.$row['name'].'">';
        echo "
            <form class='form_update' action='machines.php?view=update' method='POST'>
                <button type='submit' name='id' value='".$row['id']."'>Редактировать</button>
            </form>
            <form class='form_update' action='machines.php?view=delete' method='POST'>
                <button name='id' type='submit' value='".$row['id']."' >Удалить</button><br><br>
            </form>
            ";
        if(isset($_SESSION['idMachine'])){
            if($_SESSION['idMachine']===$row['id']){

                echo "
            <form method='POST' action='machines.php?view=save_update' enctype='multipart/form-data'>
                <input style='display: none' value='".$row['id']."' name='id'>
                <input style='display: none' value='".$row['photo']."' name='photo_path'>
                <label for='name' >Название:</label>
                <input type='text' name='name' id='name' value='".$row['name']."' required><br><br>
            
                <label for='image2'>Фото:</label>
                <input type='file' name='image2' id='image2' required><br><br>
            
                <label for='description'>Описание:</label>
                <textarea type='text' name='description' id='description' required >".$row['description']."</textarea><br><br>

                <input type='submit' value='Сохранить'>
            </form>
             
            <form action='machines.php?view=cancel' method='POST'>
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