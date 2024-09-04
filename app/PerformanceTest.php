<?php

class PerformanceTest
{

    public function __construct(private PDO $pdo)
    {
    }

    public function runQuery($query)
    {
        $start = microtime(true);
        $stmt = $this->pdo->query($query);
        $stmt->fetchAll();
        $end = microtime(true);

        return $end - $start;
    }

    public function testWithoutIndex(): void
    {
        $this->dropIndexIfExists('idx_date_of_birth_btree', 'users');
        $this->dropIndexIfExists('idx_date_of_birth_hash', 'users');

        echo "Testing without index...\n";
        $time = $this->runQuery("SELECT * FROM users WHERE date_of_birth = '1990-01-01'");
        echo "Execution time without index: " . $time . " seconds\n";
    }

    public function testWithBtreeIndex(): void
    {
        $this->pdo->exec("CREATE INDEX idx_date_of_birth_btree ON users (date_of_birth) USING BTREE");

        echo "Testing with BTREE index...\n";
        $time = $this->runQuery("SELECT * FROM users WHERE date_of_birth = '1990-01-01'");
        echo "Execution time with BTREE index: " . $time . " seconds\n";

        $this->dropIndexIfExists('idx_date_of_birth_btree', 'users');
    }

    public function testWithHashIndex(): void
    {
        $this->pdo->exec("ALTER TABLE users MODIFY date_of_birth VARCHAR(10)");

        $this->pdo->exec("CREATE INDEX idx_date_of_birth_hash ON users (date_of_birth) USING HASH");

        echo "Testing with HASH index...\n";
        $time = $this->runQuery("SELECT * FROM users WHERE date_of_birth = '1990-01-01'");
        echo "Execution time with HASH index: " . $time . " seconds\n";

        $this->dropIndexIfExists('idx_date_of_birth_hash', 'users');
    }

    private function dropIndexIfExists(string $indexName, string $tableName): void
    {
        try {
            $this->pdo->exec("DROP INDEX $indexName ON $tableName");
        } catch (\PDOException $e) {
            // Handle or log the exception if the index does not exist, if necessary
        }
    }
}