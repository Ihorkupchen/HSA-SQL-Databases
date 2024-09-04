<?php

class UserRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function insert(UserModel $user): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (name, email, date_of_birth) VALUES (?, ?, ?)');
        return $stmt->execute([$user->name, $user->email, $user->dateOfBirth]);
    }

    public function insertBatch(array $users): bool
    {
        $values = [];
        $placeholders = [];

        foreach ($users as $user) {
            $placeholders[] = "(?, ?, ?)";
            $values = array_merge($values, [$user->name, $user->email, $user->dateOfBirth]);
        }

        $sql = "INSERT INTO users (name, email, date_of_birth) VALUES " . implode(", ", $placeholders);
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }
}