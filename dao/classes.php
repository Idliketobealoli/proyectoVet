<?php

abstract class Data {}

trait Identifiable {
    protected int $id;
    public function getId(): int { return $this->id; }
    public function setId(int $id) { $this->id = $id; }
}

class User extends Data implements JsonSerializable {
    use Identifiable;

    private string $name;
    private string $surname;
    private string $email;
    private string $password;
    private string $phone;
    private int $role;
    private bool $active;
    private ?array $ownedPets;

    public function __construct(
        int $id, string $name, string $surname, 
        string $email, string $password, 
        string $phone, int $role, bool $active) {
        $this->id = $id;
        $this->setName($name);
        $this->setSurname($surname);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setPhone($phone);
        $this->setRole($role);
        $this->setActive($active);
        $this->ownedPets = null;
    }

    public function jsonSerialize(): array {
        return [
            "id" => $this->id,
            "name" => $this->name,
        ];
    }

    public function getName(): string { return $this->name; }
    public function setName(string $name) { $this->name = $name; }

    public function getOwnedPets(): array {
        if ($this->ownedPets == null) $ownedPets = DAO::getPetsByUser($this->id);
        return $ownedPets;
    }

    public function getSurname(): string { return $this->surname; }
    public function setSurname(string $surname) { $this->surname = $surname; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email) { $this->email = $email; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password) { $this->password = $password; }

    public function getPhone(): string { return $this->phone; }
    public function setPhone(string $phone) { $this->phone = $phone; }

    public function getRole(): int { return $this->role; }
    public function setRole(int $role) { $this->role = $role; }

    public function isActive(): bool { return $this->active; }
    public function setActive(bool $active) { $this->active = $active; }
}

class Pet extends Data implements JsonSerializable {
    use Identifiable;

    private string $name;
    private string $species;
    private bool $isMale;
    private bool $active;
    private int $ownerId;
    private ?User $owner;
    private ?History $history;

    public function __construct(int $id, string $name, string $species, bool $isMale, bool $active, int $ownerId) {
        $this->id = $id;
        $this->name = $name;
        $this->species = $species;
        $this->isMale = $isMale;
        $this->active = $active;
        $this->ownerId = $ownerId;
        $this->owner = null;
        $this->history = null;
    }

    public function jsonSerialize(): array {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "species" => $this->species,
            "isMale" => $this->isMale,
            "active" => $this->active,
            "ownerId" => $this->ownerId,
        ];
    }

    public function getName(): string{ return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getSpecies(): string { return $this->species; }
    public function setSpecies(string $species): void { $this->species = $species; }

    public function isMale(): bool { return $this->isMale; }
    public function setSex(string $isMale): void { $this->isMale = $isMale; }

    public function isActive(): bool { return $this->active; }
    public function setActive(bool $active): void { $this->active = $active; }

    public function getOwnerId(): int { return $this->ownerId; }
    public function setOwnerId(int $ownerId): void { $this->ownerId = $ownerId; }

    public function getOwner(): User {
        if ($this->owner == null) $owner = DAO::getUserById($this->ownerId);
        return $owner;
    }

    public function getHistory(): History {
        if ($this->history == null) $history = DAO::getHistoryByPet($this->id);
        return $history;
    }
}

class History extends Data implements JsonSerializable {
    use Identifiable;

    private string $history;
    private string $observations;
    private int $petId;
    private ?Pet $pet;

    public function __construct(int $id, string $history, string $observations, int $petId) {
        $this->id = $id;
        $this->history = $history;
        $this->observations = $observations;
        $this->petId = $petId;
        $this->pet = null;
    }

    public function jsonSerialize(): array {
        return [
            "id" => $this->id,
            "history" => $this->history,
            "observations" => $this->observations,
            "petId" => $this->petId,
        ];
    }

    public function getHistory(): string { return $this->history; }
    public function setHistory(string $history): void { $this->history = $history; }

    public function getObservations(): string { return $this->observations; }
    public function setObservations(string $observations): void { $this->observations = $observations; }

    public function getPetId(): int { return $this->petId; }
    public function setPetId(int $petId): void { $this->petId = $petId; }

    public function getPet(): Pet {
        if ($this->pet == null) $pet = DAO::getPetById($this->petId);
        return $pet;
    }
}