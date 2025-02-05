<?php
// Prüfen, ob eine Session bereits gestartet wurde
if (session_status() === PHP_SESSION_NONE) {
  // Session starten, wenn keine aktiv ist
  session_start();
}

if (!isset($_SESSION['user_id'])) {
  // Benutzer ist nicht eingeloggt, Weiterleitung zur Login-Seite
  header("Location: login.html");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($user)) {
  setcookie('email', $user['email'], time() + (86400 * 30), "/"); // 30 Tage gültig
}
/*
else {
    echo "Session ist bereits aktiv.";
}*/
/*include "startseite.php";*/

$dsn = 'mysql:host=stife.lima-db.de;dbname=db_430521_1;charset=utf8';
$username = 'USER430521';
$password = 'Sch0ed0ene';

try {
  $pdo = new PDO($dsn, $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die('Datenbankverbindung fehlgeschlagen: ' . $e->getMessage());
}

// Prüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['user_id']) && isset($_COOKIE['login_token'])) {
  $token = $_COOKIE['login_token'];

  // Benutzer anhand des Tokens abrufen
  $sql = "SELECT id FROM users WHERE login_token = :token";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':token', $token);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    // Automatische Anmeldung
    $_SESSION['user_id'] = $user['id'];
  }
}

//echo $user['email ']; //"Willkommen $user . ['id']  ! Sie sind eingeloggt.";


?>
<!DOCTYPE html>
<html lang="de">
<head>
  <!--	<link rel="stylesheet" href="css/nav.css">
  -- >
  	<link rel="stylesheet" href="css/openai-dark.css">
  <!---->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://test.die-paten.biz/index.php">
  <meta property="og:title" content="TEST-Seite">
  <meta property="og:description" content=".">
  <meta property="og:image" content="https://scontent-ham3-1.xx.fbcdn.net/v/t39.30808-6/304856006_492776829520239_4241447191520814604_n.jpg?_nc_cat=105&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=mTCF2Sx6jgMQ7kNvgF7cji-&_nc_zt=23&_nc_ht=scontent-ham3-1.xx&_nc_gid=AK2LNpPjskeOFVlx71uFycX&oh=00_AYAuhdRyZFlnUm0z0ZiKaYCE0v5VvI6eHQEkWQe-C__rkQ&oe=6799A9ED">
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>

  <?php
  $page = $_GET['page'] ?? 'home'; // Standardseite ist "home"

  switch ($page) {
    case 'home':
      include_once 'startseite.php';
      break;
    case 'expresso':
      include 'startseite.php#expresso';
      break;
    case 'aufgaben':
      include 'include/aufgabenliste/index.php';
      break;
    case 'login':
      include 'login.html';
      break;
    case 'logout':
      include 'logout.php';
      break;
    /*	default:
			include 'pages/404.php'; // Fehlerseite
			break;*/
  }

  /**	include 'include/navigation.php'; /**/
  ?>

</body>

</html>