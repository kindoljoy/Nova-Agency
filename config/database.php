<?php
/**
 * NOVA Creative Digital Agency
 * Database Connection Manager (PDO)
 */

require_once __DIR__ . '/config.php';

class Database {
    private static ?PDO $instance = null;
    private static string $driverUsed = 'none';

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            self::connect();
        }
        return self::$instance;
    }

    public static function getDriverUsed(): string {
        return self::$driverUsed;
    }

    private static function connect(): void {
        $driver = DB_DRIVER;

        // Try MySQL if specified or auto
        if ($driver === 'mysql' || $driver === 'auto') {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::ATTR_TIMEOUT            => 2, // Fast failover in auto mode
                ];
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
                self::$driverUsed = 'mysql';
                return;
            } catch (PDOException $e) {
                if ($driver === 'mysql') {
                    // Explicit MySQL failed
                    die("MySQL Connection Error: " . $e->getMessage() . "<br>Please verify database name '" . DB_NAME . "' exists or run setup.php.");
                }
                // Auto mode will fallback to SQLite below
            }
        }

        // SQLite connection (Fallback or explicitly selected)
        try {
            $sqliteDir = dirname(SQLITE_FILE);
            if (!is_dir($sqliteDir)) {
                mkdir($sqliteDir, 0777, true);
            }
            $dsn = "sqlite:" . SQLITE_FILE;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            self::$instance = new PDO($dsn, null, null, $options);
            // Enable foreign keys in SQLite
            self::$instance->exec("PRAGMA foreign_keys = ON;");
            self::$driverUsed = 'sqlite';

            // Check if tables exist, if not run auto-init
            self::autoInitSQLiteIfNeeded();
        } catch (PDOException $e) {
            die("Database Connection Error (SQLite & MySQL failed): " . $e->getMessage());
        }
    }

    private static function autoInitSQLiteIfNeeded(): void {
        $check = self::$instance->query("SELECT name FROM sqlite_master WHERE type='table' AND name='admins'")->fetch();
        if (!$check) {
            require_once ROOT_PATH . '/setup.php';
            NovaSetup::run(self::$instance, 'sqlite');
        }
    }
}

function getDB(): PDO {
    return Database::getConnection();
}

