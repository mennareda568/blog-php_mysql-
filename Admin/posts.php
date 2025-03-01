<?php
include("initials.php");
if (isset($_SESSION['login'])) {
    $page = isset($_GET['page']) ? $_GET['page'] : "1";
    $per_page = 10;
    if (is_numeric($page)) {
        $offset = ($page - 1) * $per_page;

        $statment2 = $connect->prepare("SELECT * FROM posts where `status`='0'");
        $statment2->execute();
        $pendcount = $statment2->rowCount();

        $statment = $connect->prepare("SELECT COUNT(*) FROM posts where `status`='1'");
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
                                header("refresh:3;url=posts.php");
                            }
                            ?>
                            <div class="d-flex justify-content-between ">
                                <h4 class="text-center mb-4"> Articles
                                </h4>
                                <a href="?page=pending"> Pending Articles
                                    <span class="badge badge-primary"><?php echo $pendcount ?></span>
                                </a>
                            </div>
                            <table class="table table-striped table-dark ">
                                <thead>
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Images</th>
                                        <th scope="col">Content</th>
                                        <th scope="col">User Name</th>
                                        <th scope="col">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($posts as $item) {
                                    ?>
                                        <tr>
                                            <td><?php echo $item['title'] ?></td>
                                            <?php
                                            if ($item['files'] == "No Image") {
                                            ?>
                                                <td><?php echo $item['files'] ?></td>
                                            <?php
                                            } else {
                                            ?>
                                                <td><img src="../images/<?php echo $item['files'] ?>" width="200" height="150" /></td>
                                            <?php
                                            } ?>
                                            <td>
                                                <?php
                                                $description = $item['description'];
                                                if (strlen($description) > 50) {
                                                    $short_description = substr($description, 0, 50) . '...';
                                                    echo '<span class="short-description">' . $short_description . '</span> <a href="#" class="see-more">See more</a>';
                                                    echo '<div class="full-description" style="display: none;">' . $description . '</div>';
                                                } else {
                                                    echo $description;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $id = $item['user_id'];
                                                $statment = $connect->prepare("select username from users where user_id=? ");
                                                $statment->execute(array($id));
                                                $name = $statment->fetch();
                                                echo $name['username'] ?>
                                            </td>
                                            <td>
                                                <a href="?page=show&post_id=<?php echo $item['post_id'] ?>" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>
                                                <a href="?page=edit&post_id=<?php echo $item['post_id'] ?>" class="btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <a href="?page=delete&post_id=<?php echo $item['post_id'] ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                <tfoot>
                                    <td>
                                        <?php
                                        echo ' <span style="font-size: 25px;">Pages</span>';
                                        for ($i = 1; $i <= $total_pages; $i++) {
                                            echo '<span style="font-size: 25px;"><a href="?page=' . $i . '"> ' . $i . ' | ' . '</a></span>';
                                        }
                                        ?>
                                    </td>
                                </tfoot>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        } else {
            echo '<h3 class="text-center mt-5">"No Articles To View"</h3>';
        }
    } elseif ($page == "show") {
        if (isset($_GET['post_id'])) {
            $post_id = $_GET['post_id'];
        }
        $statment = $connect->prepare("select * from posts where post_id =?");
        $statment->execute(array($post_id));
        $item = $statment->fetch();
        ?>

        <div class="container mt-5 pt-5">
            <div class="row">
                <div class="col-md-10 m-auto">
                    <table class="table table-striped table-dark">
                        <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Images</th>
                                <th scope="col">Content</th>
                                <th scope="col">Status</th>
                                <th scope="col">User Name</th>
                                <th scope="col">Category</th>
                                <th scope="col">Created_at</th>
                                <th scope="col">Updated_at</th>
                                <th scope="col">Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $item['title'] ?></td>
                                <?php
                                if ($item['files'] == "No Image") {
                                ?>
                                    <td><?php echo $item['files'] ?></td>
                                <?php
                                } else {
                                ?>
                                    <td><img src="../images/<?php echo $item['files'] ?>" width="200" height="150" /></td>
                                <?php
                                } ?>
                                <td><?php echo $item['description'] ?></td>
                                <td><?php echo $item['status'] ?></td>
                                <td>
                                    <?php
                                    $id = $item['user_id'];
                                    $statment = $connect->prepare("select username from users where user_id=? ");
                                    $statment->execute(array($id));
                                    $name = $statment->fetch();
                                    echo $name['username'] ?>
                                </td>
                                <td>
                                    <?php
                                    $id = $item['category_id'];
                                    $statment = $connect->prepare("select title from categories where category_id=? ");
                                    $statment->execute(array($id));
                                    $name = $statment->fetch();
                                    echo $name['title'] ?>
                                </td>
                                <td><?php echo $item['created_at'] ?></td>
                                <td><?php echo $item['updated_at'] ?></td>
                                <td>
                                    <a href="posts.php" class="btn btn-success"><i class="fa-solid fa-house"></i></a>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>




    <?php
    } else if ($page == "delete") {
        if (isset($_GET['post_id'])) {
            $post_id = $_GET['post_id'];
        }
        $statment = $connect->prepare("select files from posts where  post_id=?");
        $statment->execute(array($post_id));
        $item = $statment->fetch()['files'];
        unlink("../images/" . $item);

        $statment = $connect->prepare("delete from posts where  post_id=?");
        $statment->execute(array($post_id));
        $_SESSION['message'] = "deleted sucessfully";
        header("Location:posts.php");
    } else if ($page == "edit") {
        if (isset($_GET['post_id'])) {
            $post_id = $_GET['post_id'];
        }
        $statment = $connect->prepare("select * from posts where post_id=?");
        $statment->execute(array($post_id));
        $result = $statment->fetch();


    ?>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-10 m-auto">
                    <h4 class="text-center mt-4">UPDATE ARTICLE PAGE</h4>
                    <form action="?page=saveupdate" method="post">
                        <input type="hidden" name="oldid" value="<?php echo $result['post_id'] ?>" class="form-control mb-4">
                        <label>STATUS</label>
                        <select name="status" class="form-control mb-4">
                            <?php
                            if ($result['status'] == "1") {
                                echo '<option value="1" selected>active</option>';
                                echo  '<option value="0">block</option>';
                            } else {
                                echo '<option value="1" >active</option>';
                                echo  '<option value="0" selected>block</option>';
                            }
                            ?>
                        </select>
                        <input type="submit" class="form-control  btn btn-success" value="UPDATE POST">
                    </form>
                </div>
            </div>
        </div>
    <?php
    } else if ($page == "saveupdate") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $oldid = $_POST['oldid'];
            $status = $_POST['status'];
        }
        try {
            $statment = $connect->prepare("UPDATE posts
        SET `status`=?,updated_at=now()
        WHERE post_id=?");
            $statment->execute(array($status, $oldid));
            $_SESSION['message'] = "UPDATED SUCESSFULLY";
            header("Location:posts.php");
        } catch (PDOException $e) {
            header("Refresh:3;url=posts.php?page=edit&post_id=$oldid");
        }
    } else if ($page == "pending") {
        $statment = $connect->prepare("select * from posts where `status`='0' ");
        $statment->execute();
        $usercount = $statment->rowcount();
        $result = $statment->fetchall();

    ?>
        <div class="container mt-5 ">
            <div class="row">
                <div class="col-md-12 m-auto pt-5">
                    <div>
                        <?php
                        if (isset($_SESSION['message'])) {
                            echo "<h4 class='text-center alert alert-success'>" . $_SESSION['message'] . "</h4>";
                            unset($_SESSION['message']);
                            header("refresh:3;url=posts.php");
                        }
                        ?>
                        <h4 class="text-center mb-4"> Pending Approval Articles
                            <span class="badge badge-primary"><?php echo $usercount ?></span>
                            <a href="?page=active" class="btn btn-warning">Active All</a>
                        </h4>
                        <table class="table table-striped table-dark ">
                            <thead>
                                <tr>
                                    <th scope="col">Title</th>
                                    <th scope="col">Images</th>
                                    <th scope="col">Content</th>
                                    <th scope="col">User Name</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($result as $item) {
                                ?>
                                    <tr>
                                        <td><?php echo $item['title'] ?></td>
                                        <?php
                                        if ($item['files'] == "No Image") {
                                        ?>
                                            <td><?php echo $item['files'] ?></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td><img src="../images/<?php echo $item['files'] ?>" width="200" height="150" /></td>
                                        <?php
                                        } ?>
                                        <td>
                                            <?php
                                            $description = $item['description'];
                                            if (strlen($description) > 50) {
                                                $short_description = substr($description, 0, 50) . '...';
                                                echo '<span class="short-description">' . $short_description . '</span> <a href="#" class="see-more">See more</a>';
                                                echo '<div class="full-description" style="display: none;">' . $description . '</div>';
                                            } else {
                                                echo $description;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $id = $item['user_id'];
                                            $statment = $connect->prepare("select username from users where user_id=? ");
                                            $statment->execute(array($id));
                                            $name = $statment->fetch();
                                            echo $name['username'] ?>
                                        </td>
                                        <td><?php echo $item['status'] ?></td>
                                        <td>
                                            <a href="?page=show&post_id=<?php echo $item['post_id'] ?>" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>
                                            <a href="?page=edit&post_id=<?php echo $item['post_id'] ?>" class="btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="?page=delete&post_id=<?php echo $item['post_id'] ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

    <?php
    } else if ($page == "active") {
        $statment = $connect->prepare("update posts set `status`='1' ");
        $statment->execute();
        header('Location:posts.php');
    }
} else {
    $_SESSION['message'] = "Please Login First";
    header("Location:../login.php");
}
include("Includes/temp/footer.php");
    ?>