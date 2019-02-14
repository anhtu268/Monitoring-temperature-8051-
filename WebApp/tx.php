<?php
    if(isset($_GET["update"])){
        $file_count1=fopen("data/data.txt","rb");
        $data="";
        $data .= fread($file_count1,1024);
        fclose($file_count1);
        list($stt,$temp,$tempa,$chuong) = explode("%",$data);
        echo json_encode(array("stt"=>"$stt","temp"=>"$temp","tempa"=>"$tempa","chuong"=>"$chuong"));
    }
    if(isset($_GET["tempa"])){
        $tempa=$_GET["tempa"];
        $file_count2 = fopen('data/data.txt', 'rb');
        $data = '';
        $data .= fread($file_count2, 1024);
        fclose($file_count2);
        list($stt,$temp,$tempax,$chuong) = explode("%", $data);
        $line1 = "0%$temp%$tempa%$chuong";
        $file_count3 = fopen('data/data.txt', 'wb');
        fwrite($file_count3, $line1, strlen($line1));
        fclose($file_count3);
    }
    if(isset($_GET["chuong"])){
        $chuong=$_GET["chuong"];
        $file_count4 = fopen('data/data.txt', 'rb');
        $data = '';
        $data .= fread($file_count4, 1024);
        fclose($file_count4);
        list($stt,$temp,$tempa,$chuongx) = explode("%", $data);
        $line2 = "0%$temp%$tempa%$chuong";
        $file_count5 = fopen('data/data.txt', 'wb');
        fwrite($file_count5, $line2, strlen($line2));
        fclose($file_count5);
    }
?>