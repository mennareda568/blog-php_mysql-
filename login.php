<?php
include("initials.php");
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $email = $_POST['email'];
  $pass = $_POST['pass'];

  $statment = $connect->prepare("select * from users where email=?");
  $statment->execute(array($email));
  $count = $statment->rowcount();
  $item = $statment->fetch();

  if ($count > 0) {
      if (password_verify($pass, $item['password'])) {
          if ($item['status'] == 1) {
              if ($item['role'] == "admin") {
                  $_SESSION['login'] = $email;
                  header("Location:Admin/dashboard.php");
              } else if($item['role'] == "author") {
                  $_SESSION['userlogin'] = $email;                  
                  header("Location:index.php");
              }else if($item['role'] == "user") {
                $_SESSION['loginuser'] = $email;                  
                header("Location:userindex.php");
            }
          } else {
              $_SESSION['message'] = "YOUR ACCOUNT IS NOT ACTIVE";
          }
      } else {
          $_SESSION['message'] = "INVALID EMAIL OR PASSWORD";
      }
  } else {
      $_SESSION['message'] = "YOUR ACCOUNT IS NOT IN DB REGISTER FIRST";
  }
}

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
          Login Page
        </div>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
          <div class="field">
            <input type="email" name="email" required>
            <label>Email Address</label>
          </div>
          <div class="field">
            <input type="password" name="pass" required>
            <label>Password</label>
          </div>

          <div class="field">
            <input type="submit" value="Login" class="mt-1">
          </div>
          <div class="signup-link">
            <a href="register.php"> Register Page</a>
          </div>
        
        </form>
      </div>

    </div>
  </div>
</div>
