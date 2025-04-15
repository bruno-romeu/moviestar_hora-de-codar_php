<?php

    require_once('globals.php');
    require_once('db.php');
    require_once('models/movie.php');
    require_once('models/message.php');
    require_once('models/review.php');
    require_once('dao/userDAO.php');
    require_once('dao/movieDAO.php');
    require_once('dao/reviewDAO.php');

    $message = new Message($BASE_URL);
    $userDAO = new UserDAO($conn, $BASE_URL);
    $movieDAO = new MovieDAO($conn, $BASE_URL);
    $reviewDAO = new ReviewDAO($conn, $BASE_URL);

    $userData = $userDAO->verifyToken();

    $type = filter_input(INPUT_POST, "type");

    if($type === "create") {

        $rating = filter_input(INPUT_POST, "rating");
        $review = filter_input(INPUT_POST, "review");
        $movies_id = filter_input(INPUT_POST, "movies_id");
        $users_id = $userData->id;

        $reviewObject = new Review();

        $movieData = $movieDAO->findById($movies_id);

        if($movieData) {

            if(!empty($rating) && !empty($review) && !empty($movies_id)) {

                $reviewObject->rating = $rating;
                $reviewObject->review = $review;
                $reviewObject->movies_id = $movies_id;
                $reviewObject->users_id = $users_id;

                $reviewDAO->create($reviewObject);


            } else {
                $message->setMessage("Você precisa inserir a nota e comentário!", "error", "back"); 
            }

        } else{
            $message->setMessage("Informações inválidas", "error", "index.php");
        }

    } else {
        $message->setMessage("Informações inválidas", "error", "index.php");
    }
