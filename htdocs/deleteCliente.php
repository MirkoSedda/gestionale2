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
    
    $id = $_POST['clienteId']; 
    
    $sql = "DELETE FROM clienti WHERE id = '$id'";
                    
    $results = $conn->query($sql);

    $conn->close();

    echo json_encode(array("esito"=>1, "details"=>"Dati caricati correttamente"));
?>