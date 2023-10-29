<?php

require_once "classes.php";
require_once "../utils/utils.php";

class DAO {
    private static ?PDO $connection = null;

    private static function connectToDB(): PDO {
        $server = "localhost";
        $db = "clinica";
        $identifier = "root";
        $password = "";
        $options = [
            PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
        ];

        try {
            $pdo = new PDO("mysql:host=$server;dbname=$db;charset=utf8", $identifier, $password, $options);
        } catch (Exception $e) {
            error_log("DB Connection error: " . $e->getMessage());
            echo "\n\nDB Connection error:\n" . $e->getMessage();
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        }

        return $pdo;
    }

    private static function grantConnection() {
        if (self::$connection == null) {
            self::$connection = self::connectToDB();
        }
    }

    private static function executeQuery(string $sql, array $params): array {
        self::grantConnection();

        $select = self::$connection->prepare($sql);
        $select->execute($params);
        return $select->fetchAll();
    }

    // Returns:
    //   - null: if there was any error
    //   - int: generated ID
    private static function insert(string $sql, array $params): ?int {
        self::grantConnection();

        $insert = self::$connection->prepare($sql);
        $successfulSQL = $insert->execute($params);

        if (!$successfulSQL) return null;
        else return self::$connection->lastInsertId();
    }

    // Executes updates or deletes.
    // Returns:
    //   - null: if there was any error
    //   - 0, 1 or any other positive number: OK and the affected lines.
    private static function update(string $sql, array $params): ?int {
        self::grantConnection();

        $update = self::$connection->prepare($sql);
        $successfulSQL = $update->execute($params);

        if (!$successfulSQL) return null;
        else return $update->rowCount();
    }


    /* Users */
    private static function getUserFromRow(array $row): User {
        return new User($row["id"], $row["username"], $row["surname"], $row["email"], $row["password"], $row["phone"], $row["userRole"], $row["active"]);
    }

    public static function getUserById(int $id): ?User {
        $rs = self::executeQuery("SELECT * FROM users WHERE id=?", [$id]);

        if ($rs) {
            $row = $rs[0];
            return self::getUserFromRow($row);
        } else return null;
    }

    public static function getUserByEmail(string $email): ?User {
        $rs = self::executeQuery("SELECT * FROM users WHERE email=?", [$email]);

        if ($rs) {
            $row = $rs[0];
            return self::getUserFromRow($row);
        } else return null;
    }

    public static function getUsersByName(string $name): array {
        $rs = self::executeQuery("SELECT * FROM users WHERE username LIKE ? ORDER BY username", ["%".trim($name)."%"]);

        $data = [];
        foreach ($rs as $row) {
            $user = self::getUserFromRow($row);
            array_push($data, $user);
        }

        return $data;
    }

    public static function getAllUsers(?bool $active): array
    {
        if ($active === null) { $rs = self::executeQuery("SELECT * FROM users ORDER BY username", []); }
        else $rs = self::executeQuery("SELECT * FROM users WHERE active=? ORDER BY username", [$active ? 1 : 0]);

        $data = [];
        foreach ($rs as $row) {
            $user = self::getUserFromRow($row);
            array_push($data, $user);
        }

        return $data;
    }

    public static function createUser(
        string $name, string $surname,
        string $email, string $password,
        string $phone): ?User // role will always be 1 (non-admin) and active will be true by default.
    {
        $generatedId = self::insert(
            "INSERT INTO users (username, surname, email, password, phone, userRole, active) VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$name, $surname, $email, $password, $phone, 1, 1]
        );

        if ($generatedId == null) return null;
        else return self::getUserById($generatedId);
    }

    public static function updateUser(User $user): ?User {
        $rowsAffected = self::update(
            "UPDATE users SET username=?, surname=? WHERE id=?",
            [$user->getName(), $user->getSurname(), $user->getId()]
        );

        if ($rowsAffected == null) return null;
        else return $user;
    }

    public static function changePasswordUser(User $user, string $newPassword): ?User {
        $rowsAffected = self::update(
            "UPDATE users SET password=? WHERE id=?",
            [$newPassword, $user->getId()]
        );

        if ($rowsAffected == null) return null;
        else return $user;
    }

    public static function switchActivityUser(User $user): ?User {
        $rowsAffected = self::update(
            "UPDATE users SET active=? WHERE id=?",
            [$user->isActive() ? 0 : 1, $user->getId()]
        );

        if ($rowsAffected == null) return null;
        else return $user;
    }

    public static function deleteUserById(int $id): bool {
        if (self::getUserById($id)->getRole() == 0) { return false; } // this way the admin user cannot be deleted.

        $rowsAffected = self::update("DELETE FROM users WHERE id=?", [$id]);
        return ($rowsAffected == 1);
    }

    public static function deleteUser(User $user): bool {
        return self::deleteUserById($user->getId());
    }


    /* Pets */
    private static function getPetFromRow(array $row): Pet {
        return new Pet($row["id"], $row["petname"], $row["species"], $row["sex"], $row["active"], $row["ownerId"]);
    }

    public static function getPetById(int $id): ?Pet {
        $rs = self::executeQuery("SELECT * FROM pets WHERE id=?", [$id]);

        if ($rs) return self::getPetFromRow($rs[0]);
        else return null;
    }

    public static function getAllPets(?bool $active): array {
        $data = [];

        if ($active === null) { $rs = self::executeQuery("SELECT * FROM pets ORDER BY ownerId, petname", []); }
        else $rs = self::executeQuery("SELECT * FROM pets WHERE active=? ORDER BY ownerId, petname", [$active ? 1 : 0]);

        foreach ($rs as $row) {
            $pet = self::getPetFromRow($row);
            array_push($data, $pet);
        }

        return $data;
    }

    public static function getPetsByUser(int $id): array {
        $data = [];

        $rs = self::executeQuery("SELECT * FROM pets WHERE ownerId=? ORDER BY petname", [$id]);

        foreach ($rs as $row) {
            $user = self::getPetFromRow($row);
            array_push($data, $user);
        }

        return $data;
    }

    public static function getPetsByUserNames(string $username): array {
        $data = [];
        $users = self::getUsersByName($username);

        foreach ($users as $user) {
            $pets = self::getPetsByUser($user->getId());
            $data = array_merge($data, $pets);
        }

        return $data;
    }

    public static function createPet(string $name, string $species, bool $isMale, bool $isActive, int $ownerId): ?Pet {
        $generatedId = self::insert(
            "INSERT INTO pets (petname, species, sex, active, ownerId) VALUES (?, ?, ?, ?, ?)",
            [$name, $species, $isMale ? 1 : 0, $isActive ? 1 : 0, $ownerId]
        );

        if ($generatedId == null) return null;
        else {
            $historyId = self::createHistory($generatedId);
            return ($historyId == null)
                ? null
                : self::getPetById($generatedId) ;
        }
    }

    public static function updatePet(Pet $pet): ?Pet {
        $rowsAffected = self::update(
            "UPDATE pets SET petname=? WHERE id=?",
            [$pet->getName(), $pet->getId()]
        );

        if ($rowsAffected == null) return null;
        else return $pet;
    }

    public static function switchActivityPet(Pet $pet): ?Pet {
        $rowsAffected = self::update(
            "UPDATE pets SET active=? WHERE id=?",
            [$pet->isActive() ? 0 : 1, $pet->getId()]
        );

        if ($rowsAffected == null) return null;
        else return $pet;
    }

    public static function deletePetById(int $id): bool {
        $rowsAffected = self::update("DELETE FROM pets WHERE id=?", [$id]);
        return ($rowsAffected == 1);
    }

    public static function deletePet(Pet $pet): bool { return self::deletePetById($pet->getId()); }


    /* Histories */
    private static function getHistoryFromRow(array $row): History {
        return new History($row["id"], $row["history"], $row["observations"], $row["petId"]);
    }

    public static function getHistoryById(int $id): ?History {
        $rs = self::executeQuery("SELECT * FROM histories WHERE id=?", [$id]);

        if ($rs) return self::getHistoryFromRow($rs[0]);
        else return null;
    }

    public static function getHistoryByPet(int $id): ?History {
        $rs = self::executeQuery("SELECT * FROM histories WHERE petId=?", [$id]);

        if ($rs) return self::getHistoryFromRow($rs[0]);
        else return null;
    }

    private static function createHistory(int $petId): ?int
    {
        $generatedId = self::insert(
            "INSERT INTO histories (history, observations, petId) VALUES (?, ?, ?)",
            ["", "", $petId]
        );

        return ($generatedId == null)
            ? null
            : $generatedId ;
    }

    public static function updateHistory(History $history): ?History {
        $rowsAffected = self::update(
            "UPDATE histories SET history=?, observations=? WHERE petId=?",
            [$history->getHistory(), $history->getObservations(), $history->getPetId()]
        );

        if ($rowsAffected == null) return null;
        else return $history;
    }
}