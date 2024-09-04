<?php
require __DIR__ . '/../DbConnection.php';
require __DIR__ . '/../PerformanceTest.php';

$pdo = $pdo = DbConnection::default()->getPdo();

$performanceTest = new PerformanceTest($pdo);

$performanceTest->testWithoutIndex();
$performanceTest->testWithBtreeIndex();
$performanceTest->testWithHashIndex();
