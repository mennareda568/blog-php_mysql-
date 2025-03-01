<?php
include("initials.php");
if (isset($_SESSION['login'])) {
    $email = $_SESSION['login'];  
    $statment1 = $connect->prepare("SELECT * FROM users WHERE email =?");
    $statment1->execute(array($email));
    $item = $statment1->fetch();
    $_SESSION['login_id'] = $item['user_id'];
    

    $page = "All";
    if ($page == "All") {

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
    }
?>
        <div class="container">
            <div class="row">
                <div class="col-md-10 mt-5">
                    <form action="?page=password" method="post">
                        <label>Password</label>
                        <input type="hidden" name="old_id" value="<?php echo $_SESSION['login_id']; ?>" class="form-control mb-3 ">
                        <input type="password" name="pass" class="form-control mb-3">
                        <input type="submit" value="Update" class="form-control mt-3 btn btn-success ">
                    </form>
                </div>
            </div>
        </div>
<?php
    if ($page == "password") {

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $old_id = $_POST['old_id'];
            $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            try {
                $statment = $connect->prepare("UPDATE USERS SET 
           `password`=?,
            updated_at=now() 
            WHERE user_id=?");
                $statment->execute(array($pass, $old_id));
                $_SESSION['message'] = "UPDATED SUCESSFULLY";
                header("Location:users.php");
            } catch (PDOException $e) {
                $_SESSION['message'] = "Error: " . $e->getMessage();
                header("Location:users.php");
            }
        }
    }
} else {
    $_SESSION['message'] = "Please Login First";
    header("Location:../login.php");
}
include("Includes/temp/footer.php");
?>
