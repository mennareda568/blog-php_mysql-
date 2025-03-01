<?php
include("users.php");
if (isset($_SESSION['loginuser'])) {
    $page = isset($_GET['page']) ? $_GET['page'] : "1";
    $per_page = 10;
    if (is_numeric($page)) {
        $offset = ($page - 1) * $per_page;
        $statment = $connect->prepare("SELECT COUNT(*) FROM posts");
        $statment->execute();
        $total_rows = $statment->fetchColumn();
        $total_pages = ceil($total_rows / $per_page);

        $statment = $connect->prepare("SELECT * FROM posts LIMIT $offset, $per_page");
        $statment->execute();
        $posts = $statment->fetchAll();


        if (!empty($posts)) {

?>
            <div class="container mt-5 ">
                <div class="row">
                    <div class="col-md-10 m-auto pt-5">
                        <div>
                            <?php
                            if (isset($_SESSION['message'])) {
                                echo "<h4 class='text-center alert alert-success'>" . $_SESSION['message'] . "</h4>";
                                unset($_SESSION['message']);
                                header("refresh:5;url=userindex.php");
                            }
                            foreach ($posts as $item) {
                            ?>
                                <?php
                                if ($item['files'] == "No Image") {
                                ?>
                                    <?php echo $item['files'] ?>
                                <?php
                                } else {
                                ?>
                                    <img src="images/<?php echo $item['files'] ?>"
                                        style="width: 70%; height: 200px; margin: 20px auto; 
                                display: block; text-align: center;"> <?php
                                                                    } ?>

                                <br>
                                <h3 class="text-center mt-2"><?php echo $item['title'] ?></h3>
                                <br>

                                <h5>
                                    <?php
                                    $description = $item['description'];
                                    if (strlen($description) > 70) {
                                        $short_description = substr($description, 0, 70) . '...';
                                        echo '<span class="short-description">' . $short_description . '</span> <a href="#" class="see-more">See more</a>';
                                        echo '<div class="full-description" style="display: none;">' . $description . '</div>';
                                    } else {
                                        echo $description;
                                    }
                                    ?>
                                    </td>
                                    <br>
                                    <h5>

                                        <?php
                                        $id = $item['user_id'];
                                        $statment = $connect->prepare("select username from users where user_id=? ");
                                        $statment->execute(array($id));
                                        $name = $statment->fetch();
                                        ?>
                                        <span>Written By: <?php echo $name['username'] ?></span>
                                        <br>

                                        <?php
                                        $id = $item['category_id'];
                                        $statment = $connect->prepare("select title from categories where category_id=? ");
                                        $statment->execute(array($id));
                                        $name = $statment->fetch();
                                        ?>
                                        <span>Category: <?php echo $name['title'] ?></span>
                                        <br>
<br>
                                        <a href="?page=report&post_id=<?php echo $item['post_id'] ?>" class="btn btn-danger mt-2"><i class="fa-solid fa-flag"></i></a>

                                        <hr>
                                        <hr>
                                    <?php
                                }
                                echo ' <span style="font-size: 25px; color:blue;">Pages</span>';
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    echo '<span style="font-size: 25px;"><a href="?page=' . $i . '"> ' . $i . ' | ' . '</a></span>';
                                }
                                    ?>
                                    <hr>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        } else {
            echo '<h3 class="text-center mt-5">"No Articles To View"</h3>';
        }
    } elseif ($page == "report") {
        if (isset($_GET['post_id'])) {
            $post_id = $_GET['post_id'];
        }

        ?>

        <div class="container mt-5">
            <div class="row">
                <div class="col-md-10 m-auto">
                    <h4 class="text-center mt-4">REPORT PAGE</h4>
                    <form action="?page=savereport" method="post">
                        <textarea name="report" class="form-control mb-4" style="height: 120px;"></textarea>
                        <input type="hidden" name="post_id" value="<?php echo $post_id ?>">
                        <input type="submit" class="form-control  btn btn-success" value="REPORT POST">
                    </form>
                </div>
            </div>
        </div>


    <?php
    } else if ($page == "savereport") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $report = $_POST['report'];
            $post_id = $_POST['post_id'];
        }

        $statment = $connect->prepare("insert into reports (report,post_id,created_at)
            values(?,?,now())");
        $statment->execute(array($report, $post_id));
        $_SESSION['message'] = "Your report was sent sucessfully";
        header("Location:userindex.php");
    }
    ?>
<?php
} else {
    $_SESSION['message'] = "Please Login First";
    header("Location:login.php");
}
?>