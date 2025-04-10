<?php
    require_once('templates/header.php');
    require_once('dao/movieDAO.php');

    $movieDAO = new MovieDAO($conn, $BASE_URL);

    $latestMovies = $movieDAO->getLatestMovies();
    $actionMovies = [];
    $comedyMovies = [];
?>

    <div id="main-container" class="container-fluid">
        <h2 class="section-title">Filmes novos</h2>
        <p class="section-description">Veja as críticas dos últimos filmes adicionados no MovieStar</p>
        <div class="movies-container">

        <?php foreach($latestMovies as  $movie): ?>
            <?php require('templates/movie_card.php'); ?>
        <?php endforeach; ?>

        <h2 class="section-title">Ação</h2>
        <p class="section-description">Veja os melhores filmes de ação</p>
        <h2 class="section-title">Comédia</h2>
        <p class="section-description">Veja os melhores filmes de comédia</p>
    </div>

<?php
    require_once('templates/footer.php')
?>