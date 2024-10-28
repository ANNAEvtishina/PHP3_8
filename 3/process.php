<?php
//Настройки для подключения БД
$host = 'localhost';
$db = 'my_database';
$username = "root";
$password = "1";
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];
//Создаем объект PDO ("подключение к БД")
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
// Проверяем, что запрос был отправлен методом POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['name'];
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];
    // Проверка на корректность данных 
    if (empty($user_name) || empty($rating) || empty($comment)) { 
        die("Все поля обязательны для заполнения."); 
    } 
     // Подготовленный запрос для вставки данных в базу 
     $sql = "INSERT INTO reviews (username, rating, comment) VALUES (:user_name, :rating, :comment)";
         try {
       
            $stmt = $pdo->prepare($sql);
       
            $stmt->execute([ 
                ':user_name' => $user_name, 
                ':rating' => $rating, 
                ':comment' => $comment 
            ]); 
     
            echo "Отзыв успешно добавлен!"; 
        } catch (PDOException $e) { 
            echo "Error: " . $e->getMessage(); 
        } 
    $sql = "SELECT * FROM reviews";
    
    $stmt = $pdo->query($sql);
    //формируем из выборки массив данных 
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    //Вывод данных на страницу
    foreach ($reviews as $review) { 
        echo "<p><strong>{$review['username']}</strong>: {$review['comment']} (Рейтинг: 
    {$review['rating']})</p>"; 
    }
}