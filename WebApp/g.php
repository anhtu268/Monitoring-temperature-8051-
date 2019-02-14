<?php
if(isset($_GET['t'])){
    $temp=$_GET['t']+0; 
    $file_count = fopen('data/data.txt', 'rb');
    $data = '';
    $data .= fread($file_count, 4096);
    fclose($file_count);
    list($stt,$tempx,$tempa,$chuong) = explode("%", $data);
    $line = "1%$temp%$tempa%$chuong";
    $file_count2 = fopen('data/data.txt', 'wb');
    fwrite($file_count2, $line, strlen($line));
    fclose($file_count2);
    echo "\r\n";
    echo "?T$tempa\r\n";
    echo "\r\n";
    if($chuong=="1")echo "?C1\r\n"; else echo "?C0\r\n";
    echo "\r\nok\r\n";
}
?>