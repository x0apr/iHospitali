<?php
    if (!isset($_SESSION)) {
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title> iHospitali </title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="jumbotron.css" rel="stylesheet"> -->
  </head>
  <body style="background-image: linear-gradient(to bottom right,#7979d2 0%, #b3b3e6 100%);">
      <div class="container" style="padding-top: 10px;">
        <nav class="navbar navbar-static-top">
          <h1 align=center>iHospitali</h1>
            <ul class="nav nav-pills">
              <?php
                if (isset($_SESSION['username'])) {
                    echo '<a href="logout.php" style="align-items: right;"> <button class="btn btn-danger" >Logout
                  </button></a>';
                }
              ?>
            </ul>
        </nav>
        </div>
