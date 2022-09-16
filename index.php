<?php 
require_once( "database.php" );

session_start();

$sAction = $_GET['action'] ?? '';
if ( $sAction == 'logout' ) {
  // Suppression des variables de session et de la session
  $_SESSION = array();
  session_destroy();
}

if ( isset($_SESSION['utilisateur_id']) ) {
  header("Location:home.php");
}
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <title>Flop-Security - Login form</title>
  </head>
  <body>
    <div class="container-lg">

      <nav class="navbar navbar-expand-lg navbar-light bg-light my-2">
        <div class="container-fluid">
          <a class="navbar-brand" href="/">Flop-Security</a>
          
        </div>
      </nav>
      
      <div class="row mt-5 mb-3">
        <div class="col"></div>
        <div class="col-6"><h1>Please sign in</h1></div>
        <div class="col"></div>        
      </div>

      <form action="home.php" method="post">
      <div class="row mt-5 mb-3">
        <div class="col"></div>
        <div class="col-6">    
          <div class="input-group">
            <span class="input-group-text" id="basic-addon1">@</span>
            <input type="text" name="email" class="form-control" placeholder="email" aria-label="email" aria-describedby="basic-addon1">
          </div>
        </div>
        <div class="col"></div>        
      </div>
      <div class="row mt-5 mb-3">
        <div class="col"></div>
        <div class="col-6">
          <input type="password" name="password" class="form-control" id="inputPassword" placeholder="password">
        </div>
        <div class="col"></div>        
      </div>
      <div class="row mt-5 mb-3">
        <div class="col"></div>
        <div class="col-6">
        <button type="submit" class="btn btn-primary">Sign in</button>
        </div>
        <div class="col"></div>        
      </div>
      </form>

      <footer class="mt-auto text-center">
        <p>Refresh database :&nbsp;<small><?php echo $sTime2refresh; ?></small></p>
      </footer>

    </div>
    
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
  </body>
</html>