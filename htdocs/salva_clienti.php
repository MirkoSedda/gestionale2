<?php
    $request = $_REQUEST; //PHP Global variable to retrieve form data
    $nome = $request['nome']; 
    $cognome = $request['cognome']; 
    $codice_fiscale = $request['codice_fiscale'];
    $data_inserimento = date('Y-m-d H:i:s');

    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "gestionale";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_errno) {
      echo "<h6>Failed to connect to MySQL: '$conn->connect_error' </h6>";
      exit();
    }

    $sql = "INSERT INTO clienti (nome, cognome, codice_fiscale, data_inserimento) VALUES ( '$nome', '$cognome', '$codice_fiscale', '$data_inserimento')";
                    
    $results = $conn->query($sql);

    $conn->close();

    echo json_encode(array("esito"=>1, "details"=>"Dati caricati correttamente"));
?>