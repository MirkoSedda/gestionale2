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
    
    $id = $_POST['id']; 
    $nome = $_POST['nome']; 
    $cognome = $_POST['cognome']; 
    $codice_fiscale = $_POST['codice_fiscale'];

    $sql = "UPDATE clienti SET nome = '$nome', cognome = '$cognome', codice_fiscale = '$codice_fiscale', data_ultima_modifica = NULL WHERE id = '$id'";
                    
    $results = $conn->query($sql);

    $conn->close();

    echo json_encode(array("esito"=>1, "details"=>"Dati caricati correttamente"));
?>