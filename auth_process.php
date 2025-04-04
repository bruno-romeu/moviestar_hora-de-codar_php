<?php

    require_once('globals.php');
    require_once('db.php');
    require_once('models/user.php');
    require_once('models/message.php');
    require_once('dao/userDAO.php');

    $message = new Message($BASE_URL);

    $userDAO = new UserDAO($conn, $BASE_URL);

    

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

            //verificar se as senhas são identicas
            if($password === $confirmpassword) {

                //verificar se o email ja esta cadastrado no db
                if($userDAO->findByEmail($email) === false) {

                    $user = new User();

                    //criação de token e senhas
                    $userToken = $user->generateToken();
                    $finalPassword = $user->generatePassword($password);

                    $user->name = $name;
                    $user->lastname = $lastname;
                    $user->email = $email;
                    $user->password = $finalPassword;
                    $user->token = $userToken;
                    
                    $auth = true;

                    $userDAO->create($user, $auth);

                } else {
                    $message->setMessage('Usuário já cadastrado, use outro email.', 'error', 'back');
                }

            } else {
                $message->setMessage('As senhas devem ser iguais.', 'error', 'back');
            }

        //mensagem de erro pois faltam dados 
        } else {
            $message->setMessage('Por favor preencha todos os campos.', 'error', 'back');

        }


    } else if($type === 'login') {

        $email = filter_input(INPUT_POST, 'email');
        $password = filter_input(INPUT_POST, 'password');

        //tenta autenticar usuário
        if($userDAO->authenticateUser($email, $password)) {
            
            $message->setMessage('Seja bem vindo!', 'success', 'editprofile.php');

        ///redireciona caso nao esteja autenticado
        } else {
            $message->setMessage('Usuário e/ou senha incorretos.', 'error', 'back');
        }

    } else {
        $message->setMessage('Informações inválidas!', 'error', 'index.php');
    }