<?php
include("Authors/includes/temp/header.php");
session_start();
?>
<nav class="navbar navbar-expand-lg  bg-dark ">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav ml-auto">
      <?php
      if (isset($_SESSION['userlogin'])) {
      ?>
        <li class="nav-item">
          <a class="nav-link" href="index.php">Categories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="posts.php">Articles</a>
        </li>
        <li class="nav-item">
          <a href="password.php?email=<?php echo $_SESSION['userlogin'] ?>" class="btn btn-warning">Change My Password </i></a>
        </li>
      <?php
      } elseif (isset($_SESSION['loginuser'])) {
      ?>
        <li class="nav-item">
          <a href="passworduser.php?email=<?php echo $_SESSION['loginuser'] ?>" class="btn btn-warning">Change My Password </i></a>
        </li>
      <?php
      }
      ?>

      <a class="nav-link btn btn-success ml-3" href="logout.php">Log out</a>
      </li>
    </ul>
  </div>
</nav>