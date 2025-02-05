<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$dsn = 'mysql:host=stife.lima-db.de;dbname=db_430521_1;charset=utf8';
$username = 'USER430521';
$password = 'Sch0ed0ene';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Datenbankverbindung fehlgeschlagen: ' . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $rememberMe = isset($_POST['remember_me']); // Checkbox "Angemeldet bleiben"
	// Datenbank-Abfrage ob User in Datenbank vorhanden 
    $sql = "SELECT id, passwort FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['passwort'])) {
        // Login erfolgreich
        $_SESSION['user_id'] = $user['id'];

        if ($rememberMe) {
            // Token generieren
            $token = bin2hex(random_bytes(32));

            // Token in der Datenbank speichern
            $updateSql = "UPDATE users SET login_token = :token WHERE id = :id";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->bindParam(':token', $token);
            $updateStmt->bindParam(':id', $user['id']);
            $updateStmt->execute();

            // Cookie setzen
            setcookie('login_token', $token, time() + (86400 * 30), "/", "", true, true);
        }

        header("Location: index.php");
        exit;
    } else {
        echo "Ungültige E-Mail-Adresse oder Passwort.";
    }
}
?>