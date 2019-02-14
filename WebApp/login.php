<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.min.js"></script>
</head>
<body>
<?php
    if(isset($_POST["username"])){
        $username = $_POST["username"];
        $password = $_POST["password"];
        if($username == "" || $password == ""){
            echo "<div class=\"container\"><div class=\"row justify-content-center\"><div class=\"alert alert-primary alert\" role=\"alert\">
            Điền tên đăng nhập và mật khẩu!</div></div></div>";
        }
        else{
            $data = "";
            $file_count = fopen("data/login.txt","rb");
            $data .= fread($file_count,1024);
            fclose($file_count);
            list($user_check,$pass_check) = explode("%",$data);
            if(strcmp($user_check,$username) == 0 && strcmp($pass_check,$password) == 0){
                $_SESSION["username"] = $username;
                $_SESSION["password"] = $password;
                header("Location: index.php"); 
            }
            else{
                echo "<div class=\"container\"><div class=\"row justify-content-center\"><div class=\"alert alert-primary alert\" role=\"alert\">
                Sai tên đăng nhập hoặc mật khẩu!</div></div></div>";
            }
        }
    }
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4 box">
            <h2>Đăng Nhập</h2>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="text">Tên đăng nhập:</label>
                    <input type="text" class="form-control" id="user" placeholder="Điền tên đăng nhập" name="username">
                 </div>
                 <div class="form-group">
                    <label for="pwd">Mật khẩu:</label>
                    <input type="password" class="form-control" id="pwd" placeholder="Điền mật khẩu" name="password">
                </div>
                <button type="submit" class="btn btn-primary">Đăng Nhập</button>
            </form>
        </div>
    </div>
</div>
    
</body>
</html>