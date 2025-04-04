<?php

    require_once('templates/header.php');

    if($userDAO) {
        $userDAO->destroyToken();
    }

    require_once('templates/footer.php');