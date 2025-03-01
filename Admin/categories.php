<?php
include("initials.php");
if (isset($_SESSION['login'])) {

    $page = "All";
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }
    if ($page == "All") {
        $statment = $connect->prepare("select * from categories");
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
                            header("refresh:3;url=categories.php");
                        }
                        ?>
                        <h4 class="text-center mb-4">NUMBER OF CATEGORIES
                            <span class="badge badge-primary"><?php echo $usercount ?></span>
                            <a href="?page=create" class="btn btn-success">create new category</a>
                            <a href="?page=block" class="btn btn-danger">Block All</a>
                            <a href="?page=active" class="btn btn-warning">Active All</a>

                        </h4>
                        <table class="table table-striped table-dark ">
                            <thead>
                                <tr>
                                    <th scope="col">Title</th>
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
                                        <td><?php echo $item['status'] ?></td>
                                        <td>
                                            <a href="?page=show&category_id=<?php echo $item['category_id'] ?>" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>
                                            <a href="?page=edit&category_id=<?php echo $item['category_id'] ?>" class="btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="?page=delete&category_id=<?php echo $item['category_id'] ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
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
                                <th scope="col">id</th>
                                <th scope="col">Title</th>
                                <th scope="col">Status</th>
                                <th scope="col">Created_at</th>
                                <th scope="col">Updated_at</th>
                                <th scope="col">Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row"><?php echo $item['category_id'] ?></th>
                                <td><?php echo $item['title'] ?></td>
                                <td><?php echo $item['status'] ?></td>
                                <td><?php echo $item['created_at'] ?></td>
                                <td><?php echo $item['updated_at'] ?></td>
                                <td>
                                    <a href="categories.php" class="btn btn-success"><i class="fa-solid fa-house"></i></a>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
    } else if ($page == "delete") {
        if (isset($_GET['category_id'])) {
            $category_id = $_GET['category_id'];
        }
        $statment = $connect->prepare("delete from categories where  category_id=?");
        $statment->execute(array($category_id));
        $_SESSION['message'] = "deleted sucessfully";
        header("Location:categories.php");
    } else if ($page == "create") {

    ?>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-10 m-auto">
                    <h4 class="text-center mt-4">CREATE CATEGORY PAGE</h4>
                    <form action="?page=savenew" method="post">
                        <label>TITLE</label>
                        <input type="text" name="title" class="form-control mb-4">
                        <?php
                        if (isset($_SESSION['title'])) {
                            echo "<h4 class='text-center alert alert-danger'>" . $_SESSION['title'] . "</h4>";
                            unset($_SESSION['title']);
                        }
                        ?>
                        <label>STATUS</label>
                        <select name="status" class="form-control mb-4">
                            <option value="1">active</option>
                            <option value="0">block</option>
                        </select>
                        <input type="submit" class="form-control  btn btn-success" value="CREATE NEW CATEGORY">
                    </form>
                </div>
            </div>
        </div>


    <?php
    } else if ($page == "savenew") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $title = $_POST['title'];
            $status = $_POST['status'];

            $errorstext = array();
            $words = explode(' ', $title);

            if (strlen($title) == "") {
                $errorstext[] = "write a title";
            } elseif (count($words) < 1 || count($words) > 3) {
                $errorstext[] = "Title must be between 1 and 3 words";
            }

            if (empty($errorstext)) {
                try {
                    $statment = $connect->prepare("insert into categories 
                (title,`status`,created_at)
                values
                (?,?,now())");
                    $statment->execute(array($title, $status));
                    $_SESSION['message'] = "created sucessfully";
                    header("Location:categories.php");
                } catch (PDOException $e) {
                    echo "<h4 class='text-center alert alert-danger'>Error IN VALUES</h4>";
                    header("Refresh:3;url=categories.php?page=create");
                }
            } else {
                $_SESSION['title'] = implode("<br>", $errorstext);
                header("Location:categories.php?page=create");
            }
        }
    } else if ($page == "edit") {
        if (isset($_GET['category_id'])) {
            $category_id = $_GET['category_id'];
        }

        $statment = $connect->prepare("select * from categories where category_id=?");
        $statment->execute(array($category_id));
        $item = $statment->fetch();
    ?>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-10 m-auto">
                    <h4 class="text-center mt-4">UPDATE CATEGORY PAGE</h4>
                    <form action="?page=saveupdate" method="post">
                        <input type="hidden" name="oldid" value="<?php echo $item['category_id'] ?>" class="form-control mb-4">
                        <label>STATUS</label>
                        <select name="status" class="form-control mb-4">
                            <?php
                            if ($item['status'] == "1") {
                                echo '<option value="1" selected>active</option>';
                                echo  '<option value="0">block</option>';
                            } else {
                                echo '<option value="1" >active</option>';
                                echo  '<option value="0" selected>block</option>';
                            }
                            ?>
                        </select>
                        <input type="submit" class="form-control  btn btn-success" value="UPDATE CATEGORY">
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
                $statment = $connect->prepare("UPDATE categories
                SET `status`=?,updated_at=now()
                WHERE category_id=?");
                $statment->execute(array($status, $oldid));
                
                $_SESSION['message'] = "UPDATED SUCESSFULLY";
                header("Location:categories.php");
            } catch (PDOException $e) {
                header("Refresh:3;url=categories.php?page=edit&category_id=$oldid");
            }
    } else if ($page == "block") {
        $statment = $connect->prepare("update categories set `status`='0' ");
        $statment->execute();
        $_SESSION['message'] = "BLOCKED SUCESSFULLY";
        header('Location:categories.php');
    } else if ($page == "active") {
        $statment = $connect->prepare("update categories set `status`='1' ");
        $statment->execute();
        $_SESSION['message'] = "ACTIVE SUCESSFULLY";
        header('Location:categories.php');
    }
    ?>


<?php
} else {
    $_SESSION['message'] = "Please Login First";
    header("Location:../login.php");
}
include("Includes/temp/footer.php");
?>
