<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก</title>

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
    <div class="container mt-4">
        <?php include 'navbar_manager.html';?>
        <form action="check_table_qr.php" method="POST" role="form">
            <div class="form-group">
                <div class="model-center">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md 6">
                                <div class="form-group">
                                    
                                    <h5>เบอร์โต๊ะ</h5>
                                    <input type="number" class="form-control form-control-sm" name="id_table" style="width: 300px;" min="1" required>
                                    <br>
                                    
                                    <h5>จำนวนลูกค้า 199 บาท/หัว</h5>
                                    <input type="number" class="form-control form-control-sm" name="num_user" style="width: 300px;" min="1" required>
                                    <br>
                                    
                                    <button type="submit" class="btn btn-success" name="btn_ok">
                                        ตกลง
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </form>
    </div>
    
</body>
</html>