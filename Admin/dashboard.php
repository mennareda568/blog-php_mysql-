<?php
include("initials.php");
if (isset($_SESSION['login'])) {


    $statment1 = $connect->prepare("SELECT * FROM users");
    $statment1->execute();
    $usercount = $statment1->rowCount();

    $statment2 = $connect->prepare("SELECT * FROM categories");
    $statment2->execute();
    $catecount = $statment2->rowCount();

    $statment3 = $connect->prepare("SELECT * FROM posts where `status`='1'");
    $statment3->execute();
    $postcount = $statment3->rowCount();


?>

    <div class="container mt-5 pt-5">
        <div class="row ">

            <div class="col-md-3 text-center ">
                <div class="box">
                    <i class="fa-solid fa-user fa-2xl"></i>
                    <h3>USERS</h3>
                    <h4><?php echo $usercount ?></h4>
                    <a href="users.php" class="btn btn-primary">Show</a>
                </div>
            </div>


            <div class="col-md-3 text-center ">
                <div class="box">
                    <i class="fa-solid fa-shapes fa-2xl"></i>
                    <h3>CATEGROIES</h3>
                    <h4><?php echo $catecount ?></h4>
                    <a href="categories.php" class="btn btn-success">Show</a>
                </div>
            </div>


            <div class="col-md-3 text-center ">
                <div class="box">
                    <h3>ARTICLES</h3>
                    <h4><?php echo $postcount ?></h4>
                    <a href="posts.php" class="btn btn-warning">Show</a>
                </div>
            </div>

            <div class="col-md-3 text-center ">
                <div class="box">
                    <h3>REPORTS</h3>
                    <a href="reports.php" class="btn btn-primary">Show</a>
                </div>
            </div>
        </div>
    </div>


<?php
} else {
    $_SESSION['message'] = "Please Login First";
    header("Location:../login.php");
}
include("Includes/temp/footer.php");
?>
