<?php
session_start();
require_once 'database.php';
if(isset($_GET['view'])) {
    if($_GET['view']==='delete'){
        $idSubscribe = $_POST['idSubscribe'];
        $query = "DELETE FROM subscribe WHERE id=$idSubscribe;";
        $result = pg_query($dbconn,$query);
        header('Location: subscribe.php');
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/CSS/trainers.css">
    <title>Запись к тренерам</title>
</head>
<body>
<a href="admin_panel.php">На главную</a>
<h1>Запись к тренерам</h1>
<div>
    <?php
    $query = "SELECT * FROM trainers order by fio";
    $result = pg_query($dbconn, $query);
    while ($row = pg_fetch_assoc($result)) {

        $idTrainer = $row['id'];
        $html_list="";
        $query2 = "SELECT * FROM subscribe where id_trainer='$idTrainer' order by date_s ";
        $query2 ="SELECT subscribe.id AS idSubscribe, subscribe.id_trainer, subscribe.id_client, subscribe.date_s, clients.id AS idClients, clients.fio, clients.number_phone
        FROM subscribe
        JOIN clients ON subscribe.id_client = clients.id where id_trainer='$idTrainer' order by subscribe.date_s;";
        $result2 = pg_query($dbconn, $query2);
        while ($row2 = pg_fetch_assoc($result2)) {
            $html_list.= "<pre>ФИО клиента: ".$row2['fio'].", Телефон: ".$row2['number_phone']." </pre>
            <form class='form_update' action='subscribe.php?view=delete' method='POST'>
                <button name='idSubscribe' type='submit' value='".$row2['idsubscribe']."' >Удалить</button><br><br>
            </form>
";
        }
        if($html_list!==""){
            echo "<h2>Тренер: ".$row['fio']."</h2>";
            echo $html_list;
        }
    }
    ?>
</div>
<br><br><br>
<a href="admin_panel.php?view=logout">Выход</a>
</body>
</html>