<?php
    session_start();
    include "connector.php";

    echo'<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    ';

    if (isset($_REQUEST['btn_insert'])) {
        try {
        $menu_name = $_REQUEST['menu_name'];
        $menu_piece = $_REQUEST['menu_piece'];
        $qty = $_REQUEST['qty'];

        $image_file = $_FILES['menu_image']['name'];
        $type = $_FILES['menu_image']['type'];
        $size = $_FILES['menu_image']['size'];
        $temp = $_FILES['menu_image']['tmp_name'];

        $path = "upload/" . $image_file; // set upload folder path
        move_uploaded_file($temp, 'upload/'.$image_file); // บันทึกไฟล์ลงใน Floder ที่ชื่อว่า upload

            
                if (!isset($errorMsg)) {
                    $insert_stmt = $conn->prepare("INSERT INTO menu(menu_name, menu_piece, qty, menu_image) VALUES (:menu_name, :menu_piece, :qty, :fimage)");
                    $insert_stmt->bindParam(':menu_name', $menu_name);
                    $insert_stmt->bindParam(':menu_piece', $menu_piece);
                    $insert_stmt->bindParam(':qty', $qty);
                    $insert_stmt->bindParam(':fimage', $image_file);
                    
                    //header("refresh:1; url=add_menu.php");

                    if ($insert_stmt->execute()) {
                        echo'
                            <script>
                                setTimeout(function(){
                                    swal({
                                        title: "บันทึกสำเร็จ",
                                        type: "success",
                                        confirmButtonText: "ตกลง"
                                    }, function(){
                                        window.location = "add_menu.php";
                                    })
                                }, 100);
                            </script>
                        ';
                    }
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
    }

    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        $delete_stmt = $conn->query("DELETE FROM menu WHERE menu_id = $delete_id");
        $delete_stmt->execute();
        
        if ($delete_stmt) {
            echo "<script>alert('Data has been deleted successfully');</script>";
            $_SESSION['success'] = "Data has been deleted succesfully";
            header("refresh:1; url=add_menu.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มวัตถุติบ</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: Kanit ;
            font-size: 20px;
            }
        h5{
            font-size:30px;
        } 
    </style>

</head>
<body>
    <div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">เพิ่มวัตถุดิบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="add_menu.php" id="formData" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="menu_name" class="col-form-label">วัตถุดิบ:</label>
                        <input type="text" required class="form-control" name="menu_name">
                    </div>
                    <div class="mb-3">
                        <label for="menu_piece" class="col-form-label">จำนวนชิ้น/จาน:</label>
                        <input type="number" required class="form-control" name="menu_piece" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="menu_piece" class="col-form-label">จำนวนเสริฟ:</label>
                        <input type="number" required class="form-control" name="qty" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="menu_image" class="col-form-label">รูป:</label>
                        <input type="file" required class="form-control" id="imgInput" name="menu_image">
                        <img width="100%" id="previewImg" alt="">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" id="submit" name="btn_insert" class="btn btn-primary">ตกลง</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ยกเลิก</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container mt-4">
        <?php include 'navbar_manager.html';?>
            <div class="col-md-1 d-flex justify-content-end">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMenuModal">
                    เพิ่มวัตถุดิบ
                </button>
            </div><br>
            <table class="table  table-bordered table-hover table-bordered border-dark table-striped">
                <thead>
                    <tr>
                        <td align="center" width="5%">
                            รูปภาพอาหาร
                        </td>
                        <td width="60%">
                            รายละเอียด
                        </td>
                        <td align="center" width="5%">
                            แก้ไข
                        </td>
                        <td align="center" width="5%">
                            ลบ
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $select_stmt = $conn->prepare('SELECT * FROM menu'); 
                        $select_stmt->execute();
                        $menus = $select_stmt->fetchAll();

                        if (!$menus) {
                            echo "<tr><td colspan='6' class='text-center'>ไม่พบข้อมูล</td></tr>";
                        } else {
                            foreach ($menus as $menu) {
                        ?>
                        <tr>
                            <td align="center"><img src="upload/<?php echo $menu['menu_image']; ?>" width="120px" height="120px" alt="" ></td>
                            <td>
                                <div><h5><?php echo $menu['menu_name']; ?></h5></div><br>
                                <div><h6>จำนวน <?php echo $menu['menu_piece']; ?> ชิ้น/จาน </h6></div>
                                <div><h6>จำนวนเสริฟ <?php echo $menu['qty']; ?> จาน</h6></div>
                            </td>
                            <td>
                                <a href="edit_menu.php?menu_id=<?= $menu['menu_id']; ?>" class="btn btn-warning" >
                                    แก้ไข
                                </a>
                            </td>
                            <td>
                                <a data-menu_id="<?= $menu['menu_id']; ?>" href="?delete=<?= $menu['menu_id']; ?>" class="btn btn-danger delete-btn">
                                    ลบ
                                </a>
                            </td>                        
                        </tr>
                    <?php } 
                        }?>
                </tbody>
            </table>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
    <script>
        let imgInput = document.getElementById('imgInput');
        let previewImg = document.getElementById('previewImg');

        imgInput.onchange = evt => {
            const [file] = imgInput.files;
            if (file) {
                previewImg.src = URL.createObjectURL(file);
            }
        }

        $(".delete-btn").click(function(e) {
            var menuId = $(this).data('menu_id');
            e.preventDefault();
            deleteConfirm(menuId);
        })

        function deleteConfirm(menuId) {
            Swal.fire({
                title: 'ต้องการจะลบใช่ไหม',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก',
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        $.ajax({
                                url: 'add_menu.php',
                                type: 'GET',
                                data: 'delete=' + menuId,
                            })
                            .done(function() {
                                Swal.fire({
                                    title: 'ลบข้อมูลสำเร็จ',
                                    icon: 'success',
                                    confirmButtonText: "ตกลง"
                                }).then(() => {
                                    document.location.href = 'add_menu.php';
                                })
                            })
                            .fail(function() {
                                Swal.fire('Oops...', 'Something went wrong with ajax !', 'error')
                                window.location.reload();
                            });
                    });
                },
            });
        }
    </script>
</body>
</html>