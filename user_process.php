<?php

    require_once('globals.php');
    require_once('db.php');
    require_once('models/user.php');
    require_once('models/message.php');
    require_once('dao/userDAO.php');

    $message = new Message($BASE_URL);

    $userDAO = new UserDAO($conn, $BASE_URL);

    $type = filter_input(INPUT_POST, "type");

    //atualizar usuário
    if($type === "update") {

        $userData = $userDAO->verifyToken();

        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $bio = filter_input(INPUT_POST, "bio");

        //criar um novo objeto de usuário
        $user = new User();

        //preencher os dados do usuário
        $userData->name = $name;
        $userData->lastname = $lastname;
        $userData->email = $email;
        $userData->bio = $bio;

        //upload da imagem
        if(isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {

            $image = $_FILES['image'];
            $imageTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            $jpgArray = ['image/jpeg', 'image/jpg'];

            //checar tipo de imagem
            if(in_array($image['type'], $imageTypes)) {

                $imageFile = false;
                
                if(in_array($image['type'], $jpgArray)) {

                    $imageFile = imagecreatefromjpeg($image['tmp_name']);

                } else if($image['type'] === 'image/png') {
                    $imageFile = imagecreatefrompng($image['tmp_name']);
                }

                if($imageFile !== false) {
                    $imageName = $user->imageGenerateName();

                    imagejpeg($imageFile, "./img/users/" . $imageName, 100);

                    $userData->image = $imageName;
                } else {
                    $message->setMessage("Erro ao processar a imagem, tente novamente!", "error" , "back");
                }

            } else {
                $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error" , "back");
            }

        }

        $userDAO->update($userData);

    //atualiza a senha do usuário
    } else if($type === "changepassword") {

        $password = filter_input(INPUT_POST, "password");
        $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

        $userData = $userDAO->verifyToken();
        $id = $userData->id;


        if($password === $confirmpassword) {

            $user = new User();

            $finalPassword = $user->generatePassword($password);

            $user->password = $finalPassword;
            $user->id = $id;

            $userDAO->changePassword($user);

        } else {
            $message->setMessage("As senhas devem ser idênticas.", "error" , "back");
        }

    } else {
        $message->setMessage("Informações inválidas!", "error" , "index.php");
    }