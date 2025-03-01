
<?php
include("Includes/temp/header.php");
session_start();


?>
<nav class="navbar navbar-expand-lg  bg-dark ">
  <a class="navbar-brand" href="dashboard.php">DashBoard</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="users.php">Users</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="categories.php">Categories</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="posts.php">Articles</a>
      </li>
      <li class="nav-item">
        <a href="password.php?email=<?php echo $_SESSION['login'] ?>" class="btn btn-warning">Change My Password </i></a>
      </li>

      <a class="nav-link btn btn-success ml-3" href="logout.php">Log out</a>
      </li>
    </ul>
  </div>
</nav>
