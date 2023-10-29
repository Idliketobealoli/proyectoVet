<?php

function drawHeader() {
    ?>
    <header>
        <h1 class="centered">Clinica veterinaria</h1>
        <p class="centered">Welcome, <?= $_SESSION["name"] ?> - [<?= $_SESSION["email"] ?>]<br/>
            <a href='../session/closeSession.php'>Close session.</a></p>
    </header>
    <?php
}

function drawFooter() {
    ?>
    <footer><p class="right">Desarrollo de aplicaciones web en entorno Servidor - 2ºDAW 2023</p></footer>
    <?php
}