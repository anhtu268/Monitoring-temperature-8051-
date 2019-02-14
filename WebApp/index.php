<?php
  session_start();
  if(!isset($_SESSION["username"])) header("Location: login.php");
?>
<!DOCTYPE html>
<head>
<meta charset="utf-8" />
    <title>Hệ thống cảnh báo cháy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.min.js"></script>
</head>
<script>
  $(document).ready(function(){
    $("#thietlap,#chuong").addClass("btn-secondary");
    $("#thietlap,#chuong").val("Xử Lý");
    $(':input[id="thietlap"],:input[id="chuong"]').prop('disabled', true);
    setInterval(function(){
      $.get("tx.php?update=1",function(data){
        $("#temp").html(data.temp + "<strong>&deg;</strong>");
        if(data.stt=="1"){
          $("#tempa").val(data.tempa);
          $("#thietlap,#chuong").removeClass("btn-secondary");
          $("#thietlap").val("Thiết Lập");
          $(':input[id="thietlap"],:input[id="chuong"]').prop('disabled', false);
          if(data.chuong=="1"){
            $("#chuong").val("Đang Bật");
            $("#chuong").addClass("btn-primary");
            $("#chuong").removeClass("btn-light");
          }
          else{
            $("#chuong").val("Đang Tắt");
            $("#chuong").addClass("btn-light");
            $("#chuong").removeClass("btn-primary");
          }
        }
      },"json");
    },3000);
    $("#thietlap").click(function(){
      $.get("tx.php",{tempa : $("#tempa").val()});
      $("#thietlap").addClass("btn-secondary");
      $("#thietlap").val("Xử Lý");
      $(':input[id="thietlap"]').prop('disabled', true);
    });
    $("#chuong").click(function(){
      if($("#chuong").val()=="Đang Bật"){
        $.get("tx.php",{chuong : "0"});
        $("#chuong").removeClass("btn-primary");
      }
      else{
        $.get("tx.php",{chuong : "1"});
        $("#chuong").removeClass("btn-light");
      }
      $("#chuong").addClass("btn-secondary");
      $("#chuong").val("Xử Lý");
      $(':input[id="chuong"]').prop('disabled', true);
    });
  });
</script>
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
  <div class="container">
    <div class="row justify-content-center">
      <div class="de clock">
          <div class="den">
            <div class="dene">
              <div class="denem">
                <div class="deneme" id="temp">
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="form-group row">
          <label class="col-5 col-form-label">Nhiệt Độ Báo:</label>
          <div class="col-3">
            <input class="form-control" type="number" min="10" max="99" id="tempa">
          </div>
          <div class="col-4">
            <input class="form-control btn btn-primary" type="button" value="Thiết Lập" id="thietlap">
          <div>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="form-group row">
          <label class="col-5 col-form-label">Chuông Báo:</label>
          <div class="col-4 offset-3">
            <input class="form-control btn btn-primary" type="button" value="Đang Bật" id="chuong">
          <div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
