<?php

declare(strict_types=1);

session_start();

function goToMainPageIfLoggedIn() { if (checkRefreshSession()) redirect("../user/userList.php"); }

function exitIfFailedSession() { if (!checkRefreshSession()) redirect("session.php"); }

function checkRefreshSession(): bool {
    if (isRamSession()) {
        if (isset($_COOKIE["id"])) { // Basta con mirar si parece que viene cookie porque ya acabamos de validar la sesión RAM
            generateSessionCookie();
        }
        return true;
    } else { // NO hay sesión RAM
        $user = getUserByCookie();
        if ($user) { // Equivale a if ($user != null)
            generateRamSession($user); // Canjear la sesión cookie por una sesión RAM.
            generateSessionCookie();
            return true;
        } else return false;
    }
}

function isRamSession(): bool { return isset($_SESSION["id"]); }

function getUserLogin(string $email, string $password): ?array {
    $connection = connectToDB();
    $select = $connection->
    prepare("SELECT id, email, username, userRole FROM users WHERE email=? AND BINARY password=?");

    $select->execute([$email, $password]);
    $obtainedRows = $select->rowCount();

    if ($obtainedRows == 0) return null;
    else return $select->fetch();
}

function getUserByCookie(): ?array {
    if (isset($_COOKIE["id"])) {
        $connection = connectToDB();

        $select = $connection->
        prepare("SELECT id, email, username, userRole FROM users WHERE id = ? " .
            "AND BINARY cookieCode = ? AND cookieCodeExpiryDate >= ?");
        $select->execute([
            $_COOKIE["id"],
            $_COOKIE["cookieCode"],
            date("Y-m-d H:i:s", time()) // Fecha-hora de ahora mismo obtenida del sistema.
        ]);
        $obtainedRows = $select->rowCount();

        if ($obtainedRows == 0) return null;
        else return $select->fetch();
    } else return null;
}

function generateRamSession(array $user) {
    // Guardar el id es lo único indispensable.
    // El resto son por evitar accesos a la BD a cambio del riesgo
    // de que mis datos en sesión RAM estén obsoletos.
    $_SESSION["id"] = $user["id"];
    $_SESSION["email"] = $user["email"];
    $_SESSION["name"] = $user["username"];
    $_SESSION["role"] = $user["userRole"];
}

function generateSessionCookie() {
    $cookieCode = uniqid(); // Genera un código aleatorio "largo".

    $expiryDate = time() + 24 * 60 * 60;
    $expiryDateForDB = date("Y-m-d H:i:s", $expiryDate);

    // Anotar en la BD el codigoCookie y su caducidad.
    $connection = connectToDB();
    $select = $connection->prepare("UPDATE users SET cookieCode=?, cookieCodeExpiryDate=? WHERE id=?");
    $select->execute([$cookieCode, $expiryDateForDB, $_SESSION["id"]]);

    // Crear (o renovar) las cookies.
    setcookie('id', strval($_SESSION["id"]), $expiryDate);
    setcookie('cookieCode', $cookieCode, $expiryDate);
}

function closeSession() {
    // Eliminar de la BD el codigoCookie y su caducidad.
    $connection = connectToDB();
    $select = $connection->prepare("UPDATE users SET cookieCode=NULL, cookieCodeExpiryDate=NULL WHERE id=?");
    $select->execute([$_SESSION["id"]]); // Se añade el parámetro a la consulta preparada.

    // Borrar las cookies.
    setcookie('id', "", time() - 3600);
    setcookie('cookieCode', "", time() - 3600);

    // Destruir sesión RAM (implica borrar cookie de PHP "PHPSESSID").
    session_destroy();
}