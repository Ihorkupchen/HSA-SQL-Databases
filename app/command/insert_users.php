<?php

require '../UserRepository.php';
require '../UserFactory.php';
require '../DbConnection.php';

$pdo = DbConnection::default()->getPdo();

$userRepository = new UserRepository($pdo);

$userFactory = new UserFactory();

$pdo->beginTransaction();

try {
    $batchSize = 2000;
    $data = [];

    for ($i = 0; $i < 40000000; $i++) {
        $data[] = $userFactory->randomUser();

        if (($i + 1) % $batchSize==0) {
            $userRepository->insertBatch($data);
            $data = [];
            echo "Inserted " . ($i + 1) . " records\n";
        }
    }

    if (!empty($data)) {
        $userRepository->insertBatch($data);
    }

    $pdo->commit();
} catch (\Exception $e) {
    $pdo->rollBack();
    throw $e;
}

echo "Completed inserting 40 million users.\n";

