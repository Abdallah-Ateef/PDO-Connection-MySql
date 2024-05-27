<?php
try{
    $connection_db=new PDO('mysql:host=localhost;dbname=php_pdo','root','');
}catch(PDOException $e){
    echo 'connection failed';
}