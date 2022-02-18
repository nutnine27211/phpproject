<?php

    $id_table = $_POST['id_table'];
    $num_user = $_POST['num_user']; 
    $price = 199;
    if ($id_table == 1) {
        echo "<h2>โต๊ะ 1</h2>";
        echo "<img src= 'https://chart.googleapis.com/chart?chs=190x190&cht=qr&chl=localhost/projectlast/table_1.php/&choe=UTF-8'>";
        echo "$num_user x $price = ",$num_user*$price,"<br>";
      } elseif ($id_table == 2 ) {
        echo "<h2>โต๊ะ 2</h2>";
        echo "<img src= 'https://chart.googleapis.com/chart?chs=190x190&cht=qr&chl=localhost/projectlast/table_2.php/&choe=UTF-8'>";
      } elseif ($id_table == 3 ){
        echo "<h2>โต๊ะ 3</h2>";
        echo "<img src= 'https://chart.googleapis.com/chart?chs=190x190&cht=qr&chl=localhost/projectlast/table_3.php/&choe=UTF-8'>";
      }else{
        echo "<h2>มีแค่โต๊ะ 1-3 </h2>";
      }
      
?>