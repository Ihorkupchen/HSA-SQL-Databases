<?php

require '../UserFactory.php';
require '../UserRepository.php';
require '../DbConnection.php';

$pdo = DbConnection::default()->getPdo();

$user = (new UserFactory())->randomUser();

(new UserRepository($pdo))->insert($user);

echo "Inserted 1 record\n";
