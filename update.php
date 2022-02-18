<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php 

    session_start();
    include "connector.php";

    if (isset($_POST['update'])) {
        try {
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

            $sql = $conn->prepare("UPDATE users SET menu_name = :menu_name, menu_piece = :menu_piece, qty = :qty, menu_image = :img WHERE menu_id = :menu_id");
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
                            title: 'success',
                            text: 'Data updated successfully!',
                            icon: 'success',
                            timer: 5000,
                            showConfirmButton: false
                        });
                    })
                </script>";
                header("refresh:2; url=add_menu.php");
            } else {
                $_SESSION['error'] = "Data has not been updated succesfully";
                header("location: add_menu.php");
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

?>