<?php


namespace src\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    private function __construct()
    {
    }

    public static function getInstance(): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = self::connexion();
        }

        return self::$pdo;
    }

    private static function connexion(): PDO
    {
        try {
                $pdo = new PDO(
                    "pgsql:host=localhost;dbname=storemanager",
                    "postgres",
                    "1234"
                );

            $pdo->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

            $pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            return $pdo;

        } catch (PDOException $ex) {

                $sqlitePath = dirname(__DIR__) . '/../erp.php';

            $pdo = new PDO(
                "sqlite:" . $sqlitePath
            );

            $pdo->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

            $pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $pdo->exec('PRAGMA foreign_keys = ON');

            return $pdo;
        }
    }

    public static function query(
        string $sql,
        bool $single = true
    ): array {
        $pdo = self::getInstance();

        $query = $pdo->query($sql);

        $result = $single
            ? $query->fetch()
            : $query->fetchAll();

        return $result !== false ? $result : [];
    }

    public static function prepare(
        string $sql,
        array $datas
    ): \PDOStatement {
        $pdo = self::getInstance();

        $statement = $pdo->prepare($sql);
        $statement->execute($datas);

        return $statement;
    }

    public static function executeQuery(
        string $sql,
        array $datas,
        bool $single = true
    ): array {
        $statement = self::prepare($sql, $datas);

        $result = $single
            ? $statement->fetch()
            : $statement->fetchAll();

        return $result !== false ? $result : [];
    }

    public static function executeUpdate(
        string $sql,
        array $datas
    ): int {
        $pdo = self::getInstance();

        $statement = self::prepare($sql, $datas);

        if (
            str_starts_with(
                strtoupper(trim($sql)),
                'INSERT'
            )
        ) {
            return (int) $pdo->lastInsertId();
        }

        return $statement->rowCount();
    }
}