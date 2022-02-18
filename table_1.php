<?php 

    include 'connector.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โต๊ะ 1</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: Kanit;
            font-size: 20px;
            }
    </style>
</head>
<body>
    <div class="container ">
        <br>
        <h1 align="center">โต๊ะ 1</h1>

        <table class="table  table-bordered border-dark table-hover table-info table-striped">
            <thead>
                <tr class="table  table-bordered border-dark table-info">
                    <td align="center" width="5%">รูปภาพอาหาร</td>
                    <td width="50%">รายละเอียด</td>
                </tr>
            </thead>

            <tbody >
                <?php 
                    $select_stmt = $conn->prepare('SELECT * FROM menu'); 
                    $select_stmt->execute();

                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                                                
                        <td align="center"><img src="upload/<?php echo $row['menu_image']; ?>" width="120px" height="120px" alt="" ></td>
                        <td>
                            <div><h5><?php echo $row['menu_name']; ?></h5></div>
                            <div><h5>จำนวน <?php echo $row['menu_piece']; ?> ชิ้น/จาน </h5></div>
                            <input type="number" class="form-control" name="user_piece" style="width: 300px;">
                        </td>                                        
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-success" name="order_ok">สั่งอาหาร</button>
    </div>
</body>
</html>