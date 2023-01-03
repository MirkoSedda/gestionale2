<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "gestionale";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_errno) {
      echo "<h6>Failed to connect to MySQL: '$conn->connect_error' </h6>";
      exit();
    }

    $userid = $_POST['userid'];

    $sql = "SELECT * FROM clienti WHERE id=$userid";

    $results = $conn->query($sql);

    $row = $results->fetch_all(MYSQLI_ASSOC);

    $conn->close();

    echo json_encode($row);