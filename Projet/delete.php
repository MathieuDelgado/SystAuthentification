<?php 
include 'bdd.php';

echo $_POST['id'];

$response = $bdd->prepare("DELETE FROM users WHERE id = :id");
$response ->execute(array(
    'id'=>$_POST['id']
));


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete</title>
</head>
<body>
    
</body>
</html>