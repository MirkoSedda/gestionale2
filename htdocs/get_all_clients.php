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

    $sql = "SELECT * FROM `clienti` ORDER BY `data_ultima_modifica` DESC" ;

    $results = $conn->query($sql);

    $row = $results->fetch_all(MYSQLI_ASSOC);

    $conn->close();

    echo json_encode($row);
?>