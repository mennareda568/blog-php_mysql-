<?php
include("initials.php");
$page = "All";
if (isset($_GET['page'])) {
  $page = $_GET['page'];
}
if ($page == "All") {
?>
<?php
if (isset($_SESSION['message'])) {
  echo "<h4 class='text-center alert alert-danger mt-3'>" . $_SESSION['message'] . "</h4>";
  unset($_SESSION['message']);
}
?>
  <div class="container ">
    <div class="row">
      <div class="col-md-4 m-auto ">
        <div class="wrapper ">
          <div class="title">
            Register Page
          </div>
          <form action="?page=savenew" method="post">
            <div class="field">
              <input required type="text" name="username" class="form-control mb-4">
              <label>Username</label>
            </div>
            <div class="field">
              <input required type="email" name="email" class="form-control mb-4">
              <label>Email</label>
            </div>
            <div class="field">
              <input required type="password" name="pass" class="form-control mb-4">
              <label>Password</label>
            </div>

            <div class="field">
              <input type="submit" value="Register New User" class="mt-1">
            </div>
            <div class="signup-link">
              <a href="login.php"> Login Page</a>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>


<?php
} else if ($page == "savenew") {
  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
  }

  $statment = $connect->prepare("select * from users where email=?");
  $statment->execute(array($email));
  $count = $statment->rowcount();
  $item = $statment->fetch();
  if ($count > 0) {
    header("Location:register.php");
    $_SESSION['message'] = "This Account Already Registed";
  } else {
    $statment = $connect->prepare("insert into users
            (username,email,`password`,`status`,`role`,created_at)
            values(?,?,?,'1','user',now())");
    $statment->execute(array($name, $email, $pass));
    $_SESSION['loginuser'] = $email;
    header("Location:userindex.php");
  }
}
?>
