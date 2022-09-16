<?php 
require_once( "database.php" );

session_start();

if ( ! isset($_SESSION['utilisateur_id']) ) {
  header("Location:index.php");
}

openDatabase();

if ( isset($_POST['pseudo']) || isset($_POST['commentaire']) ) {
    // Insertion
    $sRequete = 'INSERT INTO commentaires(`pseudo`, `commentaire`, `date_commentaire`) VALUES("'.$_POST['pseudo'].'", "'.$_POST['commentaire'].'", '.time().')';
    $req = $bdd->prepare($sRequete);
    $req->execute();
}

$stmt = $bdd->query( 'SELECT * FROM commentaires ORDER BY date_commentaire DESC LIMIT 4', PDO::FETCH_ASSOC );
$resultat = $stmt->fetchAll();

$aCommentaires = array();
if ( $resultat !== false ) {
    $aCommentaires = $resultat;
}

if (count($aCommentaires) == 0 ) {
  $aCommentaires[] = [ 'pseudo' => 'fabrice1618', 'commentaire' => 'I was here' . "<script>alert('Hello XSS')</script>" , 'date_commentaire' => 1614450176 ];
}

closeDatabase();

function printCommentaires( $aCommentaires )
{
  $sCommentTemplate = '
  <p class="list-group-item list-group-item">
    <div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1">%s</h5>
      <small>%s</small>
    </div>
    <p class="mb-1">%s</p>
  </p>' . PHP_EOL;

  foreach ($aCommentaires as $aCommentaire) {
    $sHeure = date( "d-m-Y H:i:s", $aCommentaire['date_commentaire'] );
    printf( $sCommentTemplate, $aCommentaire['pseudo'], $sHeure, $aCommentaire['commentaire'] );
  }
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

    <title>Flop-Security - Homepage</title>
  </head>
  <body>
    <div class="container-lg">

      <nav class="navbar navbar-expand-lg navbar-light bg-light my-2">
        <div class="container-fluid">
          <a class="navbar-brand" href="/">Flop-Security</a>

          <ul class="nav justify-content-end">
            <li class="nav-item">
              <a class="nav-link" href="/home.php">Home</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="log.php">Commentaires</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/index.php?action=logout">Logout</a>
            </li>
          </ul>          
        </div>
      </nav>

      <div class="row mt-5">
        <div class="col"></div>
        <div class="card" style="width: 32rem;">
          <div class="card-body">
            <h3 class="card-title">Ajouter un commentaire</h3>
            <p class="card-text">
              <form action="log.php" method="post">
                <div class="mb-3">
                  <input type="text" name="pseudo" class="form-control" id="exampleFormControlInput1" placeholder="Votre pseudo">
                </div>
                <div class="mb-3">
                  <label for="exampleFormControlTextarea1" class="form-label">Commentaire</label>
                  <textarea name="commentaire" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                </div>
                <div class="mb-1">
                  <button type="submit" class="btn btn-primary">Save</button>
                </div>
              </form>
            </p>
          </div>
        </div>
        <div class="col"></div>

        <div class="row mt-5">
          <div class="col"></div>
          <div class="col"><h2 id="winner">Winner: fabrice1618</h2></div>
          <div class="col"></div>
        </div>
    </div>
    
    <div class="list-group list-group-flush">
<?php
printCommentaires( $aCommentaires );
?>
        <footer class="mt-auto text-center">
        <p>Refresh database :&nbsp;<small><?php echo $sTime2refresh; ?></small></p>
        </footer>

  </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
  </body>
</html>
