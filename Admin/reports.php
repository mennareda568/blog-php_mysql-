<?php
include("initials.php");
if (isset($_SESSION['login'])) {

    $page = "All";
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }
    if ($page == "All") {
        $statment = $connect->prepare("SELECT posts.post_id,COUNT(reports.id) AS NumberOfReports,report FROM posts
        LEFT JOIN reports ON posts.post_id = reports.post_id
        GROUP BY post_id
        ORDER BY NumberOfReports DESC");
        $statment->execute();
        $result = $statment->fetchall();
        $reportcount = $statment->rowCount();

?>

        <div class="container mt-5 ">
            <div class="row">
                <div class="col-md-8 m-auto pt-5">
                    <div>
                        <?php
                        if (isset($_SESSION['message'])) {
                            echo "<h4 class='text-center alert alert-success'>" . $_SESSION['message'] . "</h4>";
                            unset($_SESSION['message']);
                            header("refresh:3;url=posts.php");
                        }
                        ?>
                        <div class="d-flex justify-content-between ">
                            <h4 class="text-center mb-4"> Reports
                            </h4>
                        </div>
                        <table class="table table-striped table-dark ">
                            <thead>
                                <tr>
                                    <th scope="col"> Post_id </th>
                                    <th scope="col"> Report </th>
                                    <th scope="col"> Number of Reports </th>
                                    <th scope="col">Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($result as $item) {
                                    if ($item['NumberOfReports'] > "10") {
                                ?>
                                        <tr>
                                            <td><?php echo $item['post_id'] ?></td>
                                            <td><?php echo $item['report'] ?></td>
                                            <td><?php echo $item['NumberOfReports'] ?></td>
                                            <td>
                                                <a href="posts.php?page=show&post_id=<?php echo $item['post_id'] ?>" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>
                                                <a href="posts.php?page=edit&post_id=<?php echo $item['post_id'] ?>" class="btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <a href="posts.php?page=delete&post_id=<?php echo $item['post_id'] ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

<?php


    }
} else {
    $_SESSION['message'] = "Please Login First";
    header("Location:../login.php");
}
include("Includes/temp/footer.php");
?>
