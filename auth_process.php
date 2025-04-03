<?php

    require_once('globals.php');
    require_once('db.php');
    require_once('models/user.php');
    require_once('models/message.php');
    require_once('dao/userDAO.php');

    $message = new Message($BASE_URL);

    

    //resgata o tipo de form
    $type = filter_input(INPUT_POST, "type");

    //verifica o tipo de form
    if($type === 'register') {

        $name = filter_input(INPUT_POST, 'name');
        $lastname = filter_input(INPUT_POST, 'lastname');
        $email = filter_input(INPUT_POST, 'email');
        $password = filter_input(INPUT_POST, 'password');
        $confirmpassword = filter_input(INPUT_POST, 'confirmpassword');

        //verificação de dados
        if($name && $lastname && $email && $password) {


        //mensagem de erro pois faltam dados 
        } else {
            $message->setMessage('Por favor preencha todos os campos.', 'error', 'back');

        }


    } else if($type === 'login') {


    }