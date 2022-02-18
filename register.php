<?php
    session_start();
    include "connector.php";
    echo'<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    ';


    if (isset($_REQUEST['btn_register'])) {
        $username = strip_tags($_REQUEST['username']);
        $password = strip_tags($_REQUEST['password']);
        $firstname = strip_tags($_REQUEST['firstname']);
        $lastname = strip_tags($_REQUEST['lastname']);
        $userlevel = strip_tags($_REQUEST['userlevel']);

        if (strlen($password) < 6) {
            $errorMsg[] = "กรุณาใส่ Password มากกว่า 6 ตัว อักษร";
        }else {
            try {
                if (!isset($errorMsg)) {
                    $insert_stmt = $conn->prepare("INSERT INTO user(username, password, firstname, lastname, userlevel) VALUES (:username, :password, :firstname, :lastname, :userlevel)");
                    $insert_stmt->bindParam(':username', $username);
                    $insert_stmt->bindParam(':password', $password);
                    $insert_stmt->bindParam(':firstname', $firstname);
                    $insert_stmt->bindParam(':lastname', $lastname);
                    $insert_stmt->bindParam(':userlevel', $userlevel);
                    
                    // header("refresh:1; url=register.php");
                    
                    if ($insert_stmt->execute()) {
                        echo "<script>
                                $(document).ready(function() {
                                    Swal.fire({
                                        title: 'เพิ่มผู้ใช้งานสำเร็จ',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                })
                            </script>";
                            header("refresh:2; url=register.php");
                    }
                }
                } catch(PDOException $e) {
                    echo $e->getMessage();
                }
            }
    }

    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $delete_stmt = $conn->query("DELETE FROM user WHERE id = $id");
        $delete_stmt->execute();
        
    }       
        
    
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการบัญชีผู้ใช้งาน</title>

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
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">เพิ่มผู้ใช้งาน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="register.php" id="formData" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="menu_name" class="col-form-label">Username:</label>
                        <input type="text" required class="form-control" name="username">
                    </div>
                    <div class="mb-3">
                        <label for="menu_piece" class="col-form-label">Password:</label>
                        <input type="password" required class="form-control" name="password" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="menu_piece" class="col-form-label">ชื่อ:</label>
                        <input type="text" required class="form-control" name="firstname" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="menu_piece" class="col-form-label">นามสกุล:</label>
                        <input type="text" required class="form-control" name="lastname" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="menu_piece" class="col-form-label">พนักงาน:</label>
                        <!-- <a type="" required class="form-control" name="qty" min="1"> -->
                        <select id="userlevel" name="userlevel" class="form-control" placeholder="คำนำหน้าชื่อ" required>
                            <option value="" selected="">เลือก</option>                                      
                            <option value="m">ผู้จัดการร้าน</option>
				            <option value="e">พนักงานทั่วไป</option>
                            <option value="bks">พนักงานครัว(เนื้อ)</option>
                            <option value="vks">พนักงานครัว(ผัก)</option>
                            <option value="dks">พนักงานครัว(ของหวาน)</option>                                                                                  						                                                                                            
                            </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="submit" name="btn_register" class="btn btn-primary">ตกลง</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ยกเลิก</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <?php include 'navbar_manager.html';?><?php 
                if (isset($errorMsg)) {
                    foreach($errorMsg as $error) {
            ?>
                <div class="alert alert-danger">
                    <strong><?php echo $error; ?></strong>
                </div>
            <?php 
                    }
                }
            ?>
        <div class="col-md-1 d-flex justify-content-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    เพิ่มผู้ใช้งาน
            </button>
        </div><br>

        <table class="table  table-bordered table-hover table-bordered border-dark table-striped">
                <thead>
                    <tr>
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
                        $select_stmt = $conn->prepare('SELECT * FROM user'); 
                        $select_stmt->execute();
                        $users = $select_stmt->fetchAll();

                        if (!$users) {
                            echo "<tr><td colspan='6' class='text-center'>ไม่พบข้อมูล</td></tr>";
                        } else {
                            foreach ($users as $user) {
                        ?>
                        <tr>
                            <!-- <td align="center"><img src="upload/<?php echo $menu['menu_image']; ?>" width="120px" height="120px" alt="" ></td> -->
                            <td>
                                <div><h5><?php echo $user['username']; ?></h5></div><br>
                                <div><h6>ชื่อ <?php echo $user['firstname']; ?></h6></div>
                                <div><h6>นามสกุล <?php echo $user['lastname']; ?></h6></div>
                            </td>
                            <td>
                                <a href="register.php?id=<?= $user['id']; ?>" class="btn btn-warning" >
                                    แก้ไข
                                </a>
                            </td>
                            <td>
                                <a data-id="<?= $user['id']; ?>" href="?delete=<?= $user['id']; ?>" class="btn btn-danger delete-btn">
                                    ลบ
                                </a>
                            </td>                        
                        </tr>
                    <?php } 
                        }?>
                </tbody>
            </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>


</body>

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script>

        $(".delete-btn").click(function(e) {
            var userId = $(this).data('id');
            e.preventDefault();
            deleteConfirm(userId);
        })

        function deleteConfirm(userId) {
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
                                url: 'register.php',
                                type: 'GET',
                                data: 'delete=' + userId,
                            })
                            .done(function() {
                                Swal.fire({
                                    title: 'ลบผู้ใช้งานสำเร็จ',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    document.location.href = 'register.php';
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
</html>