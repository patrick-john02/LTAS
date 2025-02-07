<?php

$dsn = 'mysql:host=localhost;dbname=ltas_db'; 
$username = 'root';
$password = ''; 
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    
    function queryView($viewName) {
        global $pdo;
        $sql = "SELECT * FROM $viewName";  
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }
    $admins = queryView('admin');  
    
   
    foreach ($admins as $admin) {
        echo "ID: " . $admin['id'] . "<br>";
        echo "Username: " . $admin['username'] . "<br>";
        echo "Access Level: " . $admin['AccessLevel'] . "<br><br>";
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
