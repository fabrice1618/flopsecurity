<?php 

define( 'DB_TTL', 300 );    // Durée de vide de la base de données 5 min
define( 'DB_FILETIME', 'db_time.json' );    // Nom du fichier stockant le timestamp de la remise à zéro de la base
define( 'DB_PARAM', 'db_createtime' );    // parametre designant le timestamp de la remise à zéro de la base

function ajouterUtilisateur( $sEmail, $sPassword, $sType )
{
    global $bdd; 

    $sRequete = 'INSERT INTO utilisateurs(`email`, `password`, `type`) VALUES("'.$sEmail.'", "'.$sPassword.'", "'.$sType.'")';
    $req = $bdd->prepare($sRequete);
    $req->execute();
}

function openDatabase()
{
    global $bdd;

    $bdd = new PDO('mysql:host=dbflop;dbname=flopsecurity;charset=utf8', 'flopsecurity', 'pwd_flopsecurity');
    $bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}

function closeDatabase()
{
    global $bdd;

    $bdd = null;
}

function readCreateTime(string $sJSONFile)
{
    $nTime = 0;

    $aContent = json_decode( file_get_contents($sJSONFile), true );

    if ( 
        ! is_null( $aContent ) && 
        isset( $aContent[DB_PARAM] ) 
        ) {
        $nTime = $aContent[DB_PARAM];
    }

    return($nTime);
}

function writeCreateTime(string $sJSONFile, $nTime)
{

    $aContent = array();
    $aContent[DB_PARAM] = $nTime;

    file_put_contents( $sJSONFile, json_encode($aContent,JSON_PRETTY_PRINT) );
}

function dropTable($sTableName)
{
    global $bdd;

    $sRequete = sprintf( 'DROP TABLE IF EXISTS `%s`', $sTableName );
    $req = $bdd->prepare($sRequete);
    if ( ! $req->execute() ) {
        print($sRequete . " echouée");
    } 

}

function createTables()
{
    global $bdd;

    $sSQL = "
    CREATE TABLE IF NOT EXISTS `commentaires` (
        `commentaire_id` int(11) NOT NULL AUTO_INCREMENT,
        `pseudo` varchar(250) NOT NULL,
        `commentaire` varchar(2000) NOT NULL,
        `date_commentaire` int(11) NOT NULL,
        PRIMARY KEY (`commentaire_id`),
        KEY `date_commentaire` (`date_commentaire`) USING BTREE
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
    ";
    $req = $bdd->prepare($sSQL);
    $req->execute();
  
    $sSQL = "
    CREATE TABLE IF NOT EXISTS `utilisateurs` (
        `utilisateur_id` int(11) NOT NULL AUTO_INCREMENT,
        `email` varchar(250) NOT NULL,
        `password` varchar(250) NOT NULL,
        `type` varchar(5) NOT NULL,
        PRIMARY KEY (`utilisateur_id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
    ";
    $req = $bdd->prepare($sSQL);
    $req->execute();

}

function refreshDatabase()
{
    openDatabase();

    dropTable('commentaires');
    dropTable('utilisateurs');

    createTables();

    ajouterUtilisateur( "admin@example.com", "PHP", "admin" );
    ajouterUtilisateur( "user@example.com", "SQL", "user" );

    closeDatabase();
}


$bdd = null;

if ( file_exists(DB_FILETIME) ) {
    $nTime = readCreateTime(DB_FILETIME);
} else {
    refreshDatabase();
    $nTime = time();
    writeCreateTime( DB_FILETIME, $nTime );
}

$nDiff = time() - $nTime;
if ( $nDiff > DB_TTL ) {
    refreshDatabase();
    $nTime = time();
    writeCreateTime( DB_FILETIME, $nTime );
    $nDiff = 0;
}

$sTime2refresh = sprintf( "%02d:%02d", intdiv( DB_TTL - $nDiff, 60 ), (DB_TTL - $nDiff) % 60 );
