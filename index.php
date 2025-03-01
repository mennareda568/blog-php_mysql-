<?php
include("users.php");
if (isset($_SESSION['userlogin'])) {

    $email = $_SESSION['userlogin'];
    $statment1 = $connect->prepare("SELECT * FROM users WHERE email =?");
    $statment1->execute(array($email));
    $item = $statment1->fetch();
    $_SESSION['userlogin_id'] = $item['user_id'];

    $page = "All";
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }
    if ($page == "All") {
        $statment = $connect->prepare('select * from categories where `status`="1" ');
        $statment->execute();
        $usercount = $statment->rowcount();
        $result = $statment->fetchall();
       
         

?>

        <div class="container mt-5 ">
            <div class="row">
                <div class="col-md-10 m-auto pt-5">
                    <div>
                        <?php
                        if (isset($_SESSION['message'])) {
                            echo "<h4 class='text-center alert alert-success'>" . $_SESSION['message'] . "</h4>";
                            unset($_SESSION['message']);
                            header("refresh:20;url=index.php");
                        }
                        if (empty($result)) {
                            echo '<h3 class="text-center">"No Categories To View"</h3>';
                        }else{
                        
                        ?>
                        <h4 class="text-center mb-4">NUMBER OF CATEGORIES
                            <span class="badge badge-primary"><?php echo $usercount ?></span>

                        </h4>
                        <table class="table table-striped table-dark ">
                            <thead>
                                <tr>
                                    <th scope="col">Title</th>
                                    <th scope="col">Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($result as $item) {
                                ?>
                                    <tr>
                                        <td><?php echo $item['title'] ?></td>
                                        <td>
                                            <a href="?page=show&category_id=<?php echo $item['category_id'] ?>" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>
                                            <a href="?page=create&category_id=<?php echo $item['category_id'] ?>" class=" btn btn-success">create new post</a>
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
        </div>

    <?php
 }
    } else if ($page == "show") {
        if (isset($_GET['category_id'])) {
            $category_id = $_GET['category_id'];
        }
        $statment = $connect->prepare("select * from categories where category_id =?");
        $statment->execute(array($category_id));
        $item = $statment->fetch();
    ?>
        <div class="container mt-5 pt-5">
            <div class="row">
                <div class="col-md-10 m-auto">
                    <table class="table table-striped table-dark">
                        <thead>
                            <tr>

                                <th scope="col">Created_at</th>
                                <th scope="col">Updated_at</th>
                                <th scope="col">Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $item['created_at'] ?></td>
                                <td><?php echo $item['updated_at'] ?></td>
                                <td>
                                    <a href="index.php" class="btn btn-success"><i class="fa-solid fa-house"></i></a>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
    } else if ($page == "create") {

        if (isset($_GET['category_id'])) {
            $category_id = $_GET['category_id'];
        }
    ?>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-10 m-auto">
                    <h4 class="text-center mt-4">CREATE ARTICLE PAGE</h4>
                    <form action="?page=savenew" method="post" enctype="multipart/form-data">
                        <label>TITLE</label>
                        <input type="text" name="title" class="form-control mb-4"
                            value="<?php if(isset($_SESSION['old_title'])) {echo $_SESSION['old_title'];unset($_SESSION['old_title']);}?>">
                        <?php
                        if (isset($_SESSION['title'])) {
                            echo "<h4 class='text-center alert alert-danger'>" . $_SESSION['title'] . "</h4>";
                            unset($_SESSION['title']);
                        }
                        ?>

                        <label>CONTENT</label>
                        <textarea  name="desc" class="form-control mb-4" style="height: 200px;"><?php if(isset($_SESSION['old_content'])){echo $_SESSION['old_content'];unset($_SESSION['old_content']);}?></textarea>
                        <?php
                        if (isset($_SESSION['content'])) {
                            echo "<h4 class='text-center alert alert-danger'>" . $_SESSION['content'] . "</h4>";
                            unset($_SESSION['content']);
                        }
                        ?>
                        <label>Images</label>
                        <input type="file" name="img" class="form-control mb-4">
                        <?php
                        if (isset($_SESSION['file'])) {
                            echo "<h4 class='text-center alert alert-danger'>" . $_SESSION['file'] . "</h4>";
                            unset($_SESSION['file']);
                        }
                        ?>
                        <input name="category_id" type="hidden" value="<?php echo $category_id ?>">
                        <input type="submit" class="form-control  btn btn-success" value="CREATE NEW POST">
                    </form>
                </div>
            </div>
        </div>

<?php
    } else if ($page == "savenew") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $title = $_POST['title'];
            $desc = $_POST['desc'];
            $cateid = $_POST['category_id'];
            $img = $_FILES['img']['name'];

            $errorstext = array();
            $errorsdesc = array();
            $errors = array();

            $words = explode(' ', $title);
            $contentword = explode(' ', $desc);

            if (strlen($title) == "") {
                $errorstext[] = "write a title";
            } elseif (count($words) > 5) {
                $errorstext[] = "Title must be between 3 and 5 words";
            } elseif (strlen($desc) == "") {
                $errorsdesc[] = "write a content";
            } elseif (count($contentword) < 10 || count($contentword) > 100) {
                $errorsdesc[] = "Content must be between 10 and 100 words";
            } elseif ($img == "") {
                $img = "No Image";
            } elseif (file_exists("images/" . $_FILES["img"]["name"])) {
                $errors[] = "Try uploading another photo";
            } elseif ($_FILES["img"]["size"] > 4194304) {
                $errors[] = "The uploaded image exceeds the maximum allowed size of 4MB. Please upload a smaller image";
            } elseif ($_FILES["img"]["type"] != "image/png" && $_FILES["img"]["type"] != "image/jpeg") {
                $errors[] = "Only JPEG and PNG image files are allowed";
            }


            if (empty($errors)) {
                if (empty($errorsdesc)) {
                    if (empty($errorstext)) {
                        move_uploaded_file($_FILES["img"]["tmp_name"], "images/" . $_FILES['img']['name']);
                        try {
                            $statment = $connect->prepare("insert into posts 
                            (title,description,user_id,category_id,files,created_at)
                            values
                            (?,?,?,?,?,now())");
                            $statment->execute(array($title, $desc, $_SESSION['userlogin_id'], $cateid, $img));
                            $_SESSION['message'] = "Your post is awaiting admin review and approval";
                            header("Location:posts.php");
                        } catch (PDOException $e) {
                            echo "<h4 class='text-center alert alert-danger'>Error IN VALUES</h4>";
                            header("Refresh:3;url=index.php?page=create&category_id=$cateid");
                        }
                    } else {
                        $_SESSION['title'] = implode("<br>", $errorstext);
                        $_SESSION['old_content'] = $desc;
                        header("Location:index.php?page=create&category_id=$cateid");
                    }
                } else {
                    $_SESSION['content'] = implode("<br>", $errorsdesc);
                    $_SESSION['old_title'] = $title;
                    header("Location:index.php?page=create&category_id=$cateid");
                }
            } else {
                $_SESSION['file'] = implode("<br>", $errors);
                header("Location:index.php?page=create&category_id=$cateid");
            }
        }
    }
} else {
    $_SESSION['message'] = "Please Login First";
    header("Location:login.php");
}
?>