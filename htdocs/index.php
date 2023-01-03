<!DOCTYPE html>
<html lang="en">
<head>
<!--
authors: Mirko Sedda
date: 03/01/2022
last_update: 03/01/2022
-->

<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
 
</head> 
   <style>
        html,
        body {
            width: 100%;
            height: 100%;
        }
        .container {
            margin: 50px auto;
            width: 800px;
            height: 600px;
        }
        input {
            width: 400px !important;
        }
        .table-wrapper {
            max-height: 330px;
            width: auto;
            overflow: auto;
            display:inline-block;
            border: 1px solid black;
        }
        table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
        }
        table th {
            padding-top: 12px;
            padding-bottom: 12px;
            position: sticky;
            background-color: #1E90FF;
            color: black;
            position: sticky;
            top: 0;
        }
        table td, table th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        table tr:nth-child(even){
            background-color: #87CEFA;
        }

        table tr:hover {
            background-color: #00BFFF;
        }
    </style>
    <body>
        <div class="container">
            <div class="row ">
                <div class="col">
                    <h3 class="mt-2 mb-2">Registrazione cliente</h3>
                    
                    <form action="salva_clienti.php" method="POST" id="form">
                        <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label> 
                            </br>
                            <input type="text" autocomplete autofocus minlength="2" maxlength="30" class="form-control" id="nome" aria-describedby="nomeHelp" placeholder="Inserire nome" name="nome" required>
                            <span id="checkNome"class="text-danger">Inserire tra 2 e 30 caratteri.</span>
                        </div>

                        <div class="mb-3">
                            <label for="cognome" class="form-label">Cognome</label> 
                            </br>
                            <input type="text" autocomplete minlength="2" maxlength="30" class="form-control" id="cognome" aria-describedby="cognomeHelp" placeholder="Inserire cognome" name="cognome" required>
                            <span id="checkCognome" class="text-danger">Inserire tra 2 e 30 caratteri.</span>
                        </div>

                        <div class="mb-3">
                            <label for="codice_fiscale" class="form-label">
                                Codice Fiscale
                            </label> 
                            </br>
                            <input value="" type="text" autocomplete minlength="16" maxlength="16" class="form-control" id="codice_fiscale" aria-describedby="codiceFiscaleHelp" placeholder="Inserire codice fiscale" name="codice_fiscale" required>
                            <span id="checkCodiceFiscale" class="text-danger">Inserire codice fiscale corretto.</span>
                        </div>
                        <button type="button" class="btn btn-primary" id="btnSubmit" disabled>Salva</button>
                        <span id="esitoSubmit" class="alert alert-success "></span>
                    </form>

                </div>
            </div>
            <div class="row mt-2">

                <div class="col" >
                    <h3 class='mt-2 mb-3'>Lista Clienti</h3>
                    <div class="input-group mb-3">
                            <input type="text" id="filtra_clienti" class="form-control" placeholder="Ricerca per nome, cognome o codice fiscale" aria-label="Ricerca clienti" aria-describedby="filtra_clienti">
                    </div>
                    <div id="lista_clienti"></div>
                </div>

                <div id="container_modal_clienti"></div>
            </div>
        </div>
        <script>

        // Dichiarazione funzioni

        function getAllClients(){
                    $.ajax({
                        type: "GET",
                        url: "get_all_clients.php", 
                        success: function (response) {
                            let data = JSON.parse(response);

                            let listaClientiHtml = "";
                            let listaClientiJS = "";
                            let modalClientiHtml = "";
                            listaClientiHtml += `<table id="tabella_clienti" class="table-wrapper"> 
                                                    <thead>
                                                        <tr>
                                                            <th><span>Nome</span></th>
                                                            <th><span>Cognome</span></th>
                                                            <th><span>Codice fiscale</span></th>
                                                            <th><span>Aggiorna</span></th>
                                                            <th><span>Cancella</span></th>
                                                        </tr>
                                                    </thead>`;
                            if(data.length) {
                                $.each(data, function(key, value) {
                                    listaClientiHtml += `<tbody id="tbody">
                                                            <tr>
                                                                <td><span class="me-3">${value.nome}</span></td>
                                                                <td><span class="me-3">${value.cognome}</span></td>
                                                                <td><span class="me-3">${value.codice_fiscale}</span></td>
                                                                <td><i id="cliente_${value.id}" class="fa fa-pencil me-3" data-bs-toggle="modal" data-bs-target="#modal_cliente_${value.id}"></i></td>
                                                                <td><i id="elimina_cliente_${value.id}" class="fa fa-trash me-3"></i></td>
                                                            </tr>
                                                        </tbody>
                                            `;
                                    modalClientiHtml += `<div class="modal fade" id="modal_cliente_${value.id}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Aggiorna Utente</h1>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="aggiornaClienti.php" method="POST" id="form">
                                                                            <div class="mb-3">
                                                                            <label for="nome" class="form-label">Nome</label> 
                                                                                </br>
                                                                                <input type="text" autocomplete autofocus minlength="2" maxlength="30" class="form-control modal_nome" id="modal_nome_${value.id}" aria-describedby="nomeHelp" placeholder="Inserire nome" name="nome" required>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="cognome" class="form-label">Cognome</label> 
                                                                                </br>
                                                                                <input type="text" autocomplete minlength="2" maxlength="30" class="form-control modal_cognome" id="modal_cognome_${value.id}" aria-describedby="cognomeHelp" placeholder="Inserire cognome" name="cognome" required>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="codice_fiscale" class="form-label">
                                                                                    Codice Fiscale
                                                                                </label> 
                                                                                </br>
                                                                                <input value="" type="text" autocomplete minlength="16" maxlength="16" class="form-control modal_codice_fiscale" id="modal_codice_fiscale_${value.id}" aria-describedby="codiceFiscaleHelp" placeholder="Inserire codice fiscale" name="codice_fiscale" required disabled>
                                                                            </div>
                                                                            <button type="button" class="btn btn-primary modal_btn_submit" id="modal_btn_submit_${value.id}" disabled>Salva</button>
                                                                            <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Chiudi</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>`
                                }); 
                            } 

                            listaClientiHtml+= `</table>`;
                            
                            $("#container_modal_clienti").html(modalClientiHtml);
                            
                            listaClientiJS+= `<script>

                            $("[id^='elimina_cliente_']").on("click", function(e) {
                                            
                                            const clienteIdString = e.target.id;
                                            const clienteId = clienteIdString.split("_")[2];
                                            
                                            $.ajax({
                                                type: "POST", 
                                                url: "deleteCliente.php", 
                                                data: {clienteId},  
                                                dataType: "json",
                                                success:function(response){
                                                    getAllClients() 
                                            }});
                            });

                            $("[id^='cliente_']").on("click", function(e) {
                                            
                                            const clienteIdString = e.target.id;
                                            const clienteId = clienteIdString.split("_")[1];
                                            
                                            $.ajax({
                                                type: "POST", 
                                                url: "getCliente.php", 
                                                data: {clienteId},  
                                                dataType: "json",
                                                success:function(response){

                                                    const id = response[0].id;
                                                    const modalNome = response[0].nome;
                                                    const modalCognome = response[0].cognome;
                                                    const modalCodiceFiscale = response[0].codice_fiscale;

                                                    const selectorNome = '#modal_nome_' + id;
                                                    const selectorCognome = '#modal_cognome_' + id;
                                                    const selectorCodiceFiscale = '#modal_codice_fiscale_' + id;

                                                    $(selectorNome).val(modalNome);
                                                    $(selectorCognome).val(modalCognome);
                                                    $(selectorCodiceFiscale).val(modalCodiceFiscale);
                                                    $('.modal_nome').attr('old_value_nome', modalNome);
                                                    $('.modal_cognome').attr('old_value_cognome', modalCognome);
                                                    $('.modal_codice_fiscale').attr('old_value_codice_fiscale', modalCodiceFiscale);
                                                    
                                                    $("[id^='modal_nome_']").keyup(function(){
                                                        if($('.modal_nome').attr('old_value_nome') !== $('.modal_nome').val()){ 
                                                            $(".modal_btn_submit").removeAttr('disabled');
                                                        } else {
                                                            $(".modal_btn_submit").attr('disabled', true)
                                                        }
                                                    });

                                                    $("[id^='modal_cognome_']").keyup(function(){
                                                        if($('.modal_cognome').attr('old_value_cognome') !== $('.modal_cognome').val()){ 
                                                            $(".modal_btn_submit").removeAttr('disabled');
                                                        } else {
                                                            $(".modal_btn_submit").attr('disabled', true)
                                                        }
                                                    });
                                                    
                                                    // $("[id^='modal_codice_fiscale']").keyup(function(){
                                                    //     if($('.modal_codice_fiscale').attr('old_value_codice_fiscale') !== $('.modal_codice_fiscale').val()){ 
                                                    //         $(".modal_btn_submit").removeAttr('disabled');
                                                    //     } else {
                                                    //         $(".modal_btn_submit").attr('disabled', true)
                                                    //     }
                                                    // });
                                                   
                                                }
                                        });

                                        $("[id^='modal_btn_submit_']").on("click", function(e) {
                                                        const id = $(this).attr("id").split("_")[3];
                                                        const selectorNome = '#modal_nome_' + id;
                                                        const selectorCognome = '#modal_cognome_' + id;
                                                        const selectorCodiceFiscale = '#modal_codice_fiscale_' + id;   
                                                        
                                                        let formData = {
                                                            id,
                                                            nome: $(selectorNome).val(),
                                                            cognome: $(selectorCognome).val(),
                                                            codice_fiscale: $(selectorCodiceFiscale).val(),
                                                        };
                                                        
                                                        $.ajax({
                                                            type: "POST", 
                                                            url: "aggiornaClienti.php", 
                                                            data: formData, 
                                                            dataType: "json",
                                                            beforeSend: function () { 
                                                                $("[id^='modal_btn_submit_']").attr('disabled', true).html("Processing...");
                                                            },
                                                            success:function(response){
                                                                setTimeout(() => { 
                                                                    $("#esitoModalSubmit").html(response.details);
                                                                    $("#esitoModalSubmit").show();
                                                                    getAllClients();
                                                                }, 500);

                                                                setTimeout(() => { 
                                                                    $("#esitoModalSubmit").hide();
                                                                }, 1000);
                                                                setTimeout(() => { 
                                                                    $('.modal-backdrop').hide(); 
                                                                }, 1500);
                                                        }
                                        })
                                    })
                                })
                                
                                <\/script>`;
                                
                                listaClientiHtml += listaClientiJS;

                                $("#lista_clienti").html(listaClientiHtml);
                            
                        }
                    });
        }

        $(document).ready(function() {
            $("#checkNome").hide();
            $("#checkCognome").hide();
            $("#checkCodiceFiscale").hide();
            $("#esitoSubmit").hide();
            getAllClients();
        });

        $("#filtra_clienti").on("keyup", function() {
            let value = $(this).val().toLowerCase();
            if(value.length !== 1){
                $("#tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            }
        });

        $("#nome").keyup(function () {
            let stringaNome = $("#nome").val();
            let valoreNome = stringaNome.trim()
            if (valoreNome.length < 1){
                $("#checkNome").hide();
                $("#btnSubmit").attr('disabled', true);
            } else if (/^[A-Za-z\s]*$/.test(valoreNome) === false) {
                $("#checkNome").show();
                $("#checkNome").html("Inserire solo lettere e/o spazi.")
                $("#btnSubmit").attr('disabled', true);
            } else if (valoreNome.length < 2 || valoreNome.length > 30) {
                $("#checkNome").show();
                $("#btnSubmit").attr('disabled', true);

            } else {
                $("#checkNome").hide();
                $("#btnSubmit").attr('disabled', false);

            }                    
        });

        $("#cognome").keyup(function () {
            let stringaCognome = $("#cognome").val();
            let valoreCognome = stringaCognome.trim()
            if (valoreCognome.length < 1){
                $("#checkCognome").hide();
                $("#btnSubmit").attr('disabled', true);
            } else if (/^[A-Za-z\s]*$/.test(valoreCognome) === false) {
                $("#checkCognome").show();
                $("#checkCognome").html("Inserire solo lettere e/o spazi.")
                $("#btnSubmit").attr('disabled', true);
            } else if (valoreCognome.length < 2 || valoreCognome.length > 30) {
                $("#checkCognome").show();
                $("#btnSubmit").attr('disabled', true);
            } else {
                $("#checkCognome").hide();
                $("#btnSubmit").attr('disabled', false);

            }                    
        });
       
        $("#codice_fiscale").keyup(function () {
            let stringaCodiceFiscale = $("#codice_fiscale").val();
            let valoreCodiceFiscale = stringaCodiceFiscale.trim();
            let codici_fiscali = []
            $('#tabella_clienti tr td:nth-child(3)').each(function () {
                codici_fiscali.push($(this).text())
            });
            if (codici_fiscali.length < 1){
                $("#checkCodiceFiscale").hide();
                $("#btnSubmit").attr('disabled', true);
            } else if(codici_fiscali.includes(valoreCodiceFiscale)){
                $("#checkCodiceFiscale").show().html("Codice fiscale gia' presente.");
                $("#btnSubmit").attr('disabled', true);
            } else if (/^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$/.test(valoreCodiceFiscale) === false) {
                $("#checkCodiceFiscale").show().html("Inserire codice fiscale corretto.");
                $("#btnSubmit").attr('disabled', true);
            } else {
                $("#checkCodiceFiscale").hide();
                $("#btnSubmit").attr('disabled', false);
            } 
        });

        $("#nome, #cognome, #codice_fiscale").on("keyup", function() {
            if ($("#nome").val().length < 1 || $("#cognome").val().length < 1 || $("#codice_fiscale").val().length < 1){
                $("#btnSubmit").attr('disabled', true);
            }
        });

        $("#btnSubmit").on("click", function() {
            let formData = {
                nome: $("#nome").val(),
                cognome: $("#cognome").val(),
                codice_fiscale: $("#codice_fiscale").val(),
            };

            $.ajax({
                type: "POST", 
                url: "salva_clienti.php", 
                data: formData, 
                dataType: "json",
                beforeSend: function () { 
                    $("#btnSubmit").attr('disabled', true).html("Processing...");
                },
                success:function(response){
                   
                    setTimeout(() => { 
                        $("#esitoSubmit").html(response.details);
                        $("#esitoSubmit").show();
                        $("#btnSubmit").attr('disabled', false).html("Salva");
                        $("#nome").val("");
                        $("#cognome").val("");
                        $("#codice_fiscale").val("");
                        getAllClients();
                    }, 500);

                    setTimeout(() => { 
                        $("#esitoSubmit").hide();
                    }, 1000);
                
                }
            });
        });
        
         </script>
    </body>
</html>  