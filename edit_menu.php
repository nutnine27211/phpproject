<?php 
    session_start();
    include "connector.php";
    echo'<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    ';

    if (isset($_POST['update'])) {
        
            $menu_id = $_POST['menu_id'];
            $menu_name = $_POST['menu_name'];
            $menu_piece = $_POST['menu_piece'];
            $qty = $_POST['qty'];
            $img = $_FILES['img'];

            $img2 = $_POST['img2'];
            $upload = $_FILES['img']['name'];

            if ($upload != '') {
                $allow = array('jpg', 'jpeg', 'png');
                $extension = explode(".", $img['name']);
                $fileActExt = strtolower(end($extension));
                $fileNew = rand() . "." . $fileActExt;
                $filePath = "upload/".$fileNew;

                if (in_array($fileActExt, $allow)) {
                    if ($img['size'] > 0 && $img['error'] == 0) {
                        move_uploaded_file($img['tmp_name'], $filePath);
                    }
                }
            } else {
                $fileNew = $img2;
            }

            $sql = $conn->prepare("UPDATE menu SET menu_name = :menu_name, menu_piece = :menu_piece, qty = :qty, menu_image = :img WHERE menu_id = :menu_id");
            $sql->bindParam(":menu_id", $menu_id);
            $sql->bindParam(":menu_name", $menu_name);
            $sql->bindParam(":menu_piece", $menu_piece);
            $sql->bindParam(":qty", $qty);
            $sql->bindParam(":img", $fileNew);
            $sql->execute();

            if ($sql) {
                echo "<script>
                    $(document).ready(function() {
                        Swal.fire({
                            title: 'อัปเดทสำเร็จ',
                            icon: 'success',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    })
                </script>";
                header("refresh:2; url=add_menu.php");
            } else {
                $_SESSION['error'] = "Data has not been updated succesfully";
                header("location: add_menu.php");
            }
        } 
    
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไข วัตถุดิบ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200&display=swap" rel="stylesheet">
    <style>
        .container {
            max-width: 550px;
        }
        body{
            font-family: Kanit ;
            font-size:22px;
        }
    </style>

</head>
<body>

    <div class="container mt-5">
        <h1>แก้ไข วัตถุดิบ</h1>
        <hr>
        <form action="" method="post" enctype="multipart/form-data">
            <?php 
                if (isset($_GET['menu_id'])) {
                    $menu_id = $_GET['menu_id'];
                    $stmt = $conn->query("SELECT * FROM menu WHERE menu_id = $menu_id");
                    $stmt->execute();
                    $data = $stmt->fetch();
                }
            ?>
            <div class="mb-3">
                <input type="hidden" readonly value="<?= $data['menu_id']; ?>" required class="form-control" name="menu_id">
                <label for="menu_name" class="col-form-label">วัตถุดิบ:</label>
                <input type="text" value="<?= $data['menu_name']; ?>" required class="form-control" name="menu_name">
                <input type="hidden" value="<?= $data['menu_image']; ?>" required class="form-control" name="img2">
            </div>
            <div class="mb-3">
                <label for="menu_piece" class="col-form-label">จำนวนชิ้น:</label>
                <input type="number"  value="<?= $data['menu_piece']; ?>" required class="form-control" name="menu_piece" min="1">
            </div>
            <div class="mb-3">
                <label for="qty" class="col-form-label">จำนวนเสริฟ:</label>
                <input type="number"  value="<?= $data['qty']; ?>" required class="form-control" name="qty" min="1">
            </div>
            <div class="mb-3">
                <label for="menu_image" class="col-form-label">รูป:</label>
                <input type="file" class="form-control" id="imgInput" name="img">
                <img width="100%" src="upload/<?= $data['menu_image']; ?>" id="previewImg" alt="">
            </div>

            <div class="modal-footer">
                <button type="submit" name="update" class="btn btn-success">อัปเดท</button>
                <a class="btn btn-secondary" href="add_menu.php">กลับ</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let imgInput = document.getElementById('imgInput');
        let previewImg = document.getElementById('previewImg');

        imgInput.onchange = evt => {
            const [file] = imgInput.files;
            if (file) {
                previewImg.src = URL.createObjectURL(file);
            }
        }
    </script>

</body>
</html>