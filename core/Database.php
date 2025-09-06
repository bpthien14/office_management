<?php
/**
 * Database Class
 * Quản lý kết nối cơ sở dữ liệu sử dụng Singleton pattern
 */

class Database
{
    private static $instance = null;
    private $connection;
    
    private function __construct()
    {
        $config = require CONFIG_PATH . '/database.php';
        
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            $this->connection = new PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                die("Kết nối cơ sở dữ liệu thất bại: " . $e->getMessage());
            } else {
                die("Không thể kết nối cơ sở dữ liệu. Vui lòng thử lại sau.");
            }
        }
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection()
    {
        return $this->connection;
    }
    
    /**
     * Thực thi query với prepared statement
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                throw new Exception("Lỗi database: " . $e->getMessage());
            } else {
                throw new Exception("Có lỗi xảy ra. Vui lòng thử lại sau.");
            }
        }
    }
    
    /**
     * Lấy tất cả records
     */
    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Lấy một record
     */
    public function fetch($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    /**
     * Lấy số lượng records
     */
    public function count($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    
    /**
     * Thực thi insert và trả về ID
     */
    public function insert($sql, $params = [])
    {
        $this->query($sql, $params);
        return $this->connection->lastInsertId();
    }
    
    /**
     * Thực thi update/delete và trả về số rows bị ảnh hưởng
     */
    public function execute($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    
    /**
     * Bắt đầu transaction
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit()
    {
        return $this->connection->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback()
    {
        return $this->connection->rollback();
    }
    
    /**
     * Kiểm tra kết nối
     */
    public function isConnected()
    {
        return $this->connection !== null;
    }
}
