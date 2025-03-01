<?php
include("users.php");
if (isset($_SESSION['userlogin'])) {
    $page = isset($_GET['page']) ? $_GET['page'] : "1";
    $per_page = 5;
    if (is_numeric($page)) {
        $offset = ($page - 1) * $per_page;
        $statment = $connect->prepare("SELECT COUNT(*) FROM posts where `status`='1'");
        $statment->execute();
        $total_rows = $statment->fetchColumn();
        $total_pages = ceil($total_rows / $per_page);

        $statment = $connect->prepare("SELECT * FROM posts where `status`='1' LIMIT $offset, $per_page");
        $statment->execute();
        $posts = $statment->fetchAll();


        if (!empty($posts)) {


?>





            <div class="container">
                <?php if (isset($_SESSION['message'])) { ?>
                    <h4 class="text-center alert alert-success"><?php echo $_SESSION['message']; ?></h4>
                    <?php unset($_SESSION['message']); ?>
                    <?php header("refresh:20;url=posts.php"); ?>
                <?php } ?>

                <h1 class="text-center">Articles</h1>

                <div class="row">
                    <?php foreach ($posts as $item) { ?>
                        <div class="col-md-4">
                            <div class="card mt-3">
                                <?php if ($item['files'] == "No Image") { ?>
                                    <h6 class="text-center mt-3"><?php echo $item['files']; ?></h6>
                                <?php } else { ?>
                                    <img src="images/<?php echo $item['files']; ?>" class="card-img-top">
                                <?php } ?>

                                <div class="card-body">
                                    <h5 class="card-title text-center"><?php echo $item['title']; ?></h5>
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
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <?php $id = $item['user_id']; ?>
                                            <?php $statment = $connect->prepare("select username from users where user_id=? "); ?>
                                            <?php $statment->execute(array($id)); ?>
                                            <?php $name = $statment->fetch(); ?>
                                            Written By : <?php echo $name['username']; ?>
                                            <br>
                                            <?php $id = $item['category_id']; ?>
                                            <?php $statment = $connect->prepare("select title from categories where category_id=? "); ?>
                                            <?php $statment->execute(array($id)); ?>
                                            <?php $name = $statment->fetch(); ?>
                                            Category : <?php echo $name['title']; ?>
                                        </small>
                                    </p>


                                    <?php if ($_SESSION['userlogin_id'] == $item['user_id']) { ?>
                                        <h5 class="card-title text-center">
                                            <a href="?page=edit&post_id=<?php echo $item['post_id']; ?>" class="btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="?page=update&post_id=<?php echo $item['post_id']; ?>" class="btn btn-warning">Update Image</a>
                                            <a href="?page=delete&post_id=<?php echo $item['post_id']; ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                        </h5>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>


                    <div class="col-md-12 text-center">
                        <table>
                            <tr class="pages-title"> <td>Pages</td> </tr>
                                <tr>
                                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                        <td class="pages-item">
                                            <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </td>
                                    <?php } ?>
                                </tr>
                        </table>
                    </div>

                </div>

            <?php
        } else {
             if (isset($_SESSION['message'])) { ?>
                <h4 class="text-center alert alert-success"><?php echo $_SESSION['message']; ?></h4>
                <?php unset($_SESSION['message']); ?>
                <?php header("refresh:20;url=posts.php"); ?>
            <?php } 
            echo '<h3 class="text-center mt-5">"No Articles To View"</h3>';
        }
    } else if ($page == "delete") {
        if (isset($_GET['post_id'])) {
            $post_id = $_GET['post_id'];
        }
        $statment = $connect->prepare("select files from posts where  post_id=?");
        $statment->execute(array($post_id));
        $item = $statment->fetch()['files'];
        unlink("images/" . $item);

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
                            <label>TITLE</label>
                            <input type="text" name="title" class="form-control mb-4"
                                value="<?php if (isset($_SESSION['old_title'])) {
                                            echo $_SESSION['old_title'];
                                            unset($_SESSION['old_title']);
                                        } else {
                                            echo $result['title'];
                                        } ?>">
                            <?php
                            if (isset($_SESSION['title'])) {
                                echo "<h4 class='text-center alert alert-danger'>" . $_SESSION['title'] . "</h4>";
                                unset($_SESSION['title']);
                            }
                            ?>
                            <label>CONTENT</label>
                            <textarea name="desc" class="form-control mb-4" style="height: 200px;"><?php if (isset($_SESSION['old_content'])) {
                                                                                                        echo $_SESSION['old_content'];
                                                                                                        unset($_SESSION['old_content']);
                                                                                                    } else {
                                                                                                        echo $result['description'];
                                                                                                    } ?></textarea>
                            <?php
                            if (isset($_SESSION['content'])) {
                                echo "<h4 class='text-center alert alert-danger'>" . $_SESSION['content'] . "</h4>";
                                unset($_SESSION['content']);
                            }
                            ?>
                            <input type="submit" class="form-control  btn btn-success" value="UPDATE POST">
                        </form>
                    </div>
                </div>
            </div>
        <?php
    } else if ($page == "saveupdate") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $oldid = $_POST['oldid'];
            $title = $_POST['title'];
            $desc = $_POST['desc'];

            $errorstext = array();
            $errorsdesc = array();

            $words = explode(' ', $title);
            $contentword = explode(' ', $desc);

            if (strlen($title) == "") {
                $errorstext[] = "write a title";
            } elseif (count($words) > 5) {
                $errorstext[] = "Title must be between 2 and 5 words";
            } elseif (strlen($desc) == "") {
                $errorsdesc[] = "write a content";
            } elseif (count($contentword) < 10 || count($contentword) > 100) {
                $errorsdesc[] = "Content must be between 10 and 100 words";
            }
            if (empty($errorsdesc)) {
                if (empty($errorstext)) {
                    try {
                        $statment = $connect->prepare("UPDATE posts
                    SET title=?,description=?,updated_at=now()
                    WHERE post_id=?");
                        $statment->execute(array($title, $desc, $oldid));
                        $_SESSION['message'] = "UPDATED SUCESSFULLY";
                        header("Location:posts.php");
                    } catch (PDOException $e) {
                        echo "<h4 class='text-center alert alert-danger'>DUPLICATED IN VALUES</h4>";
                        header("Refresh:20;url=posts.php?page=edit&post_id=$oldid");
                    }
                } else {
                    $_SESSION['title'] = implode("<br>", $errorstext);
                    $_SESSION['old_content'] = $desc;
                    header("Location:posts.php?page=edit&post_id=$oldid");
                }
            } else {
                $_SESSION['content'] = implode("<br>", $errorsdesc);
                $_SESSION['old_title'] = $title;
                header("Location:posts.php?page=edit&post_id=$oldid");
            }
        }
    } else if ($page == "update") {
        if (isset($_GET['post_id'])) {
            $post_id = $_GET['post_id'];
        }
        $statment = $connect->prepare("select * from posts where post_id=?");
        $statment->execute(array($post_id));
        $result = $statment->fetch();
        ?>

            <form action="?page=saveimage" class="m-auto" method="post" enctype="multipart/form-data">
                <input type="hidden" name="oldid" value="<?php echo $result['post_id'] ?>">
                <input type="file" name="img" class="form-control mb-4">
                <?php
                if (isset($_SESSION['file'])) {
                    echo "<h4 class='text-center alert alert-danger'>" . $_SESSION['file'] . "</h4>";
                    unset($_SESSION['file']);
                }
                ?>
                <input type="submit" class="btn btn-success" value="Update ">
            </form>
            </div>
            </div>
            </div>

        <?php
    } else if ($page == "saveimage") {

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $id = $_POST['oldid'];
            $img = $_FILES['img']['name'];


            try {

                $statment = $connect->prepare("select files from posts where  post_id=?");
                $statment->execute(array($id));
                $item = $statment->fetch()['files'];
                unlink("images/" . $item);


                $errors = array();
                if ($img == "") {
                    $img = "No Image";
                } elseif (file_exists("images/" . $_FILES["img"]["name"])) {
                    $errors[] = "Try uploading another photo";
                } elseif ($_FILES["img"]["size"] > 4194304) {
                    $errors[] = "The uploaded image exceeds the maximum allowed size of 4MB. Please upload a smaller image";
                } elseif ($_FILES["img"]["type"] != "image/png" && $_FILES["img"]["type"] != "image/jpeg") {
                    $errors[] = "Only JPEG and PNG image files are allowed";
                }

                if (empty($errors)) {
                    move_uploaded_file($_FILES['img']['tmp_name'], "images/" . $_FILES['img']['name']);

                    $stmt = $connect->prepare("UPDATE posts SET files = ? WHERE post_id = ?");
                    $stmt->execute(array($img, $id));
                    $_SESSION['message'] = "Image updated successfully";
                    header("Location:posts.php");
                } else {
                    $_SESSION['file'] = implode("<br>", $errors);
                    header("Location:posts.php?page=update&post_id=$id");
                }
            } catch (PDOException $e) {
                echo "<h4 class='text-center alert alert-danger'>Error IN VALUES</h4>";
                header("Refresh:3;url=posts.php?page=update&post_id=$id");
            }
        }
    }
        ?>
    <?php

} else {
    $_SESSION['message'] = "Please Login First";
    header("Location:login.php");
}
    ?>