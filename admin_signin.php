<?php
session_start();
require_once 'database.php';
$query = "SELECT password_hash FROM admin_auth WHERE username = $1";
$result = pg_query_params($dbconn, $query, array('admin'));
$row = pg_fetch_assoc($result);
$password_hash = $row['password_hash'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="">
    <title>Вход</title>
</head>
<body>
    <div>
        <?php
        if(isset($_GET['view'])){
            $pageGET = $_GET['view'];
            if($pageGET == 'panel') {
                header('Location: admin_panel.php');
            }
            elseif ($pageGET === 'signin'){
                $userPassword = $_POST['password'];
                if (crypt($userPassword, $password_hash) === $password_hash){
                    $_SESSION['user'] ='admin';
                    header('Location: admin_panel.php');
                }
                else{
                    $_SESSION['message'] = 'Ошибка';
                    header('Location: admin_signin.php');
                }
            }
        }
        else{
            if(isset($_GET['user'])){
                if($_GET['user']==='admin'){
                    header('Location: admin_signin.php?view=panel');
                }
            }

            echo '
                <form class="adminForm" method="POST" action="admin_signin.php?view=signin">
                    <label for="username">Имя пользователя:</label>
                    <input type="text" name="username" id="username" required><br><br>
                    <label for="password">Пароль:</label>
                    <input type="password" name="password" id="password" required><br><br>
                    <input type="submit" value="Войти">
                </form>
                ';
            if(isset($_SESSION['message'])){
                echo $_SESSION['message'];
            }
            unset($_SESSION['message']);
        }

        ?>


    </div>


</body>
</html>
