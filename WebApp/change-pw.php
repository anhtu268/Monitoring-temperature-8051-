<?php
    session_start();
    if(!isset($_SESSION["username"])) header("Location: login.php");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Đổi Mật Khẩu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.min.js"></script>
</head>
<body>
    <div>
        <nav class="navbar navbar-light bg-light">    
            <a class="btn btn-primary" rolepe="button" href="index.php">Trang chủ</a>
            <div class="btn-group" role="group" aria-label="Basic example">
            <a href="change-pw.php" class="btn btn-outline-primary">Đổi mật khẩu</a>
            <a href="logout.php" class="btn btn-outline-danger">Đăng xuất</a>
            </div>
        </nav>
    </div>
    <?php
        if(isset($_POST["pass_old"])){
            $user_new = $_POST["user_new"];
            $pass_old = $_POST["pass_old"];
            $pass_new = $_POST["pass_new"];
            $pass_new_check = $_POST["pass_new_check"];
            if($pass_old == "" || $pass_new == "" || $pass_new_check == "" || $user_new == ""){
                echo "<div class=\"container\"><div class=\"row justify-content-center\"><div class=\"alert alert-primary alert\" role=\"alert\">
                Vui lòng điền đầy đủ thông tin!</div></div></div>";
            }
            else{
                if(strcmp($pass_new,$pass_new_check)) echo "<div class=\"container\"><div class=\"row justify-content-center\"><div class=\"alert alert-primary alert\" role=\"alert\">
                Vui lòng nhập lại mật khẩu mới!</div></div></div>";
                else{
                    $file_count = fopen("data/login.txt","rb");
                    $data = ""; $data .= fread($file_count,1024);
                    fclose($file_count);
                    list($user,$pass_check) = explode("%",$data);
                    if(strcmp($pass_old,$pass_check)) echo "<div class=\"container\"><div class=\"row justify-content-center\"><div class=\"alert alert-primary alert\" role=\"alert\">
                    Sai mật khẩu vui lòng nhập lại!</div></div></div>";
                    else{
                        $data_new = "$user_new%$pass_new";
                        $file_count_new = fopen("data/login.txt","wb");
                        fwrite($file_count_new,$data_new,strlen($data_new));
                        fclose($file_count_new);
                        echo "<div class=\"container\"><div class=\"row justify-content-center\"><div class=\"alert alert-success alert\" role=\"alert\">
                        Thay đổi thông tin thành công!</div></div></div>";
                    }
                }
            }
        }

    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-offset-4 col-md-4 box">
                <h2>Đổi Thông Tin</h2>
                <form action="change-pw.php" method="post">
                    <div class="form-group">
                        <label for="text">Tên đăng nhập mới:</label>
                        <input type="text" class="form-control" id="user" placeholder="Điền tên đăng nhập mới" name="user_new">
                    </div>
                    <div class="form-group">
                        <label for="pwd">Mật khẩu cũ:</label>
                        <input type="password" class="form-control"  placeholder="Điền mật khẩu cũ" name="pass_old">
                    </div>
                    <div class="form-group">
                        <label for="pwd">Mật khẩu mới:</label>
                        <input type="password" class="form-control"  placeholder="Điền mật khẩu mới" name="pass_new">
                    </div>
                    <div class="form-group">
                        <label for="pwd">Nhập lại mật khẩu mới:</label>
                        <input type="password" class="form-control"  placeholder="Nhập lại mật khẩu" name="pass_new_check">
                    </div>
                    <button type="submit" class="btn btn-primary">Thay Đổi</button>
                </form>
            </div>
        </div>
    </div>
</body>