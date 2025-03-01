<?php
include("initials.php");
if (isset($_SESSION['login'])) {

    $page = "All";
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }
    if ($page == "All") {
        $statment1 = $connect->prepare("SELECT * FROM users ");
        $statment1->execute();
        $usercount = $statment1->rowCount();
        $result = $statment1->fetchall();
?>

        <div class="container ">
            <div class="row">
                <div class="col-md-10 m-auto pt-5">
                    <?php
                    if (isset($_SESSION['message'])) {
                        echo "<h4 class='text-center alert alert-success'>" . $_SESSION['message'] . "</h4>";
                        unset($_SESSION['message']);
                        header("Refresh:4;url=users.php");
                    }
                    ?>
                    <div class="d-flex justify-content-between">
                        <h4 class="text-center">Users <span class="badge badge-primary"><?php echo $usercount ?></span>
                        <a class="badge badge-success" href="?page=create">Ceate New User</a>
                    </h4>
                    <form action="?page=search" method="post">
                        <div class="input-group">
                            <input type="search" name="search" >
                            <span class="input-group-prepend">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </span>
                        </div>
                    </form>
                </div>
                    <table class="table table-dark text-center mt-3">
                        <thead>
                            <tr>
                                <th scope="col">NAME</th>
                                <th scope="col">EMAIL</th>
                                <th scope="col">STATUS</th>
                                <th scope="col">ROLE</th>
                                <th scope="col">OPERATION</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($result as $item) {
                            ?>
                                <tr>
                                    <td><?php echo $item['username'] ?></td>
                                    <td><?php echo $item['email'] ?></td>
                                    <td><?php echo $item['status'] ?></td>
                                    <td><?php echo $item['role'] ?></td>
                                    <td><a href="?page=show&user_id=<?php echo $item['user_id'] ?>" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>
                                        <a href="?page=edit&user_id=<?php echo $item['user_id'] ?>" class="btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="?page=delete&user_id=<?php echo $item['user_id'] ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
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
    } else if ($page == "show") {
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
        }

        $statment1 = $connect->prepare("SELECT * FROM users WHERE	user_id =?");
        $statment1->execute(array($user_id));
        $item = $statment1->fetch();
    ?>

        <div class="container ">
            <div class="row">
                <div class="col-md-10 m-auto pt-5">
                    <table class="table table-dark mt-5 pt-5 text-center">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">NAME</th>
                                <th scope="col">EMAIL</th>
                                <th scope="col">STATUS</th>
                                <th scope="col">ROLE</th>
                                <th scope="col">CREATED_AT</th>
                                <th scope="col">UPDATED_AT</th>
                                <th scope="col">OPERATION</th>

                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td><?php echo $item['user_id'] ?></td>
                                <td><?php echo $item['username'] ?></td>
                                <td><?php echo $item['email'] ?></td>
                                <td><?php echo $item['status'] ?></td>
                                <td><?php echo $item['role'] ?></td>
                                <td><?php echo $item['created_at'] ?></td>
                                <td><?php echo $item['updated_at'] ?></td>
                                <td><a href="users.php" class="btn btn-primary"><i class="fa-solid fa-arrow-right"></i></a></td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    <?php

    } else if ($page == "search") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $username= $_POST['search'];

        $statment = $connect->prepare("SELECT * FROM users WHERE username =?");
        $statment->execute(array($username));
        $result = $statment->fetchall();
        }
        if ($result) {
?>
        <table class="table table-dark text-center mt-5 pt-5">
        <thead>
            <tr>
                <th scope="col">NAME</th>
                <th scope="col">EMAIL</th>
                <th scope="col">STATUS</th>
                <th scope="col">ROLE</th>
                <th scope="col">OPERATION</th>

            </tr>
        </thead>
       <tbody>
                            <?php
                            foreach ($result as $item) {
                            ?>
                                <tr>
                                    <td><?php echo $item['username'] ?></td>
                                    <td><?php echo $item['email'] ?></td>
                                    <td><?php echo $item['status'] ?></td>
                                    <td><?php echo $item['role'] ?></td>
                                    <td><a href="?page=show&user_id=<?php echo $item['user_id'] ?>" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>
                                        <a href="?page=edit&user_id=<?php echo $item['user_id'] ?>" class="btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="?page=delete&user_id=<?php echo $item['user_id'] ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                        <a href="users.php" class="btn btn-primary">Back To Users Table</a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
    </table>
    <?php
        }else{
            $_SESSION['message']="user is not in database";
            header('Location:users.php');
        }

    } else if ($page == "delete") {
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
        }

        $statment = $connect->prepare("DELETE FROM users WHERE user_id =?");
        $statment->execute(array($user_id));
        $_SESSION['message'] = "Deleted Sucessfully";
        header("Location:users.php");
    } else if ($page == "create") {

    ?>
        <div class="container">
            <div class="row">
                <div class="col-md-10 mt-5">
                    <h4 class="text-center">CREATE USER PAGE</h4>
                    <form action="?page=savenew" method="post">
                        <label>USERNAME</label>
                        <input type="text" name="name" class="form-control mb-3">
                        <label>EMAIL</label>
                        <input type="email" name="email" class="form-control mb-3">
                        <label>PASSWORD</label>
                        <input type="password" name="pass" class="form-control mb-3">
                        <label>STATUS</label>
                        <select name="status" class="form-control mb-3">
                            <option value="1">active</option>
                            <option value="0">block</option>
                        </select>
                        <label>ROLE</label>
                        <select name="role" class="form-control mb-3">
                            <option value="admin">admin</option>
                            <option value="user">user</option>
                        </select>
                        <input type="submit" value="Create New User" class="form-control mt-3 btn btn-success ">

                    </form>
                </div>
            </div>
        </div>

    <?php

    } else if ($page == "savenew") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $pass = $_POST['pass'];
            $status = $_POST['status'];
            $role = $_POST['role'];

            $statment = $connect->prepare("select * from users where email=?");
            $statment->execute(array($email));
            $count = $statment->rowcount();
            $item = $statment->fetch();
            if ($count > 0) {
                echo "<h4 class='text-center alert alert-danger'>This Account Already Registed</h4>";
                header("Refresh:3;url=users.php?page=create");
            } else {
                try {
                    $statment = $connect->prepare("insert into users 
                    (username,email,`password`,`status`,`role`,created_at)
                    values(?,?,?,?,?,now())");
                    $statment->execute(array( $name, $email, $pass, $status, $role));
                    $_SESSION['message'] = "CREATED SUCESSFULLY";
                    header("Location:users.php");
                } catch (PDOException $e) {
                    echo "<h4 class='text-center alert alert-danger'>DUPLICATED IN ID</h4>";
                    header("Refresh:3;url=users.php?page=create");
                }
            }
        }
    } else if ($page == "edit") {
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
        }
        $statment = $connect->prepare("SELECT * FROM users WHERE user_id =?");
        $statment->execute(array($user_id));
        $item = $statment->fetch();

    ?>
        <div class="container">
            <div class="row">
                <div class="col-md-10 mt-5">
                    <h4 class="text-center">UPDATE USER PAGE</h4>
                    <form action="?page=update" method="post">
                        <input type="hidden" name="old_id" value="<?php echo $item['user_id']; ?>" class="form-control mb-3 ">
                        <label>USERNAME</label>
                        <input type="text" name="name" value="<?php echo $item['username']; ?>" class="form-control mb-3">
                        <label>EMAIL</label>
                        <input type="email" name="email" value="<?php echo $item['email']; ?>" class="form-control mb-3">
                        <label>STATUS</label>
                        <select name="status" class="form-control mb-3">
                            <?php
                            if ($item['status'] == 1) {
                                echo '<option value="1" selected>active</option>';
                                echo '<option value="0">block</option>';
                            } else {

                                echo '<option value="1" >active</option> ';
                                echo  '<option value="0" selected>block</option>';
                            }
                            ?>
                        </select>
                        <label>ROLE</label>
                        <select name="role" class="form-control mb-3">
                            <?php
                            if ($item['role'] == "admin") {

                                echo '<option value="admin" selected>admin</option> ';
                                echo  '<option value="user">user</option>';
                            } else {
                                echo '<option value="admin">admin</option> ';
                                echo  '<option value="user" selected>user</option>';
                            }
                            ?>
                        </select>
                        <input type="submit" value="Update User" class="form-control mt-3 btn btn-success ">

                    </form>
                </div>
            </div>
        </div>
    <?php
    } else if ($page == "update") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $old_id = $_POST['old_id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $status = $_POST['status'];
            $role = $_POST['role'];

            $statment = $connect->prepare("select email from users where user_id = ?");
            $statment->execute(array($old_id));
            $old_email = $statment->fetchColumn();

            if ($email != $old_email) {
                $statment = $connect->prepare("select * from users where email=?");
                $statment->execute(array($email));
                $count = $statment->rowcount();
                $item = $statment->fetch();

                if ($count > 0) {
                    echo "<h4 class='text-center alert alert-danger'>This Account Already Registed</h4>";
                    header("Refresh:3;url=users.php?page=edit&user_id=$old_id");
                    exit;
                }
            }

            try {
                $statment = $connect->prepare("UPDATE USERS SET username=?, email=?, `status`=?, `role`=?, updated_at=now() WHERE user_id=?");
                $statment->execute(array($name, $email, $status, $role, $old_id));
                $_SESSION['message'] = "UPDATED SUCESSFULLY";
                header("Location:users.php");
            } catch (PDOException $e) {
                echo "<h4 class='text-center alert alert-danger'>DUPLICATED IN ID</h4>";
                header("Refresh:3;url=users.php?page=edit&user_id=$old_id");
            }
        }
    }
    ?>
<?php
} else {
    $_SESSION['message'] = "Please Login First";
    header("Location:../login.php");
}
include("Includes/temp/footer.php");
?>
