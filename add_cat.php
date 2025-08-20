<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['catName'];
    $color = $_POST['catBreed'];
    $age = $_POST['catAge'];
    $gender = $_POST['catGender'];

    $sql = "INSERT INTO cats (name, color, age, gender)
            VALUES ('$name', '$color', '$age', '$gender')";

    if ($conn->query($sql) === TRUE) {
        header("Location: cat_menu.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
