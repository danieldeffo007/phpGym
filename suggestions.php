<?php
session_start();
require_once 'database.php';
if(isset($_GET['view'])) {
    if ($_GET['view']==='delete'){
        $idsuggestions = $_POST['idsuggestions'];
        $query = "DELETE FROM suggestions WHERE id=$idsuggestions;";
        $result = pg_query($dbconn,$query);
        header('Location: suggestions.php');
    }


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="CSS/repair.css">
    <title>Предложения и пожелания</title>
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
<h1>Предложения и пожелания</h1>
<div>
    <?php
    $query = "SELECT suggestions.id AS idsuggestions, suggestions.description, suggestions.date_sug, clients.fio  FROM suggestions 
    JOIN clients ON suggestions.id_client = clients.id order by date_sug";
    $result = pg_query($dbconn, $query);
    while ($row = pg_fetch_assoc($result)) {
        echo "
            <div class='block faq'>
                <details class='faq-details'>
                <summary class='faq-question'>".$row['fio']."</summary>
                <div class='faq-answer'>
                <p>Сообщение: ".$row['description']."</p>
            <form action='suggestions.php?view=delete' method='POST'>
                <button name='idsuggestions' type='submit' value='".$row['idsuggestions']."' >Удалить</button>
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