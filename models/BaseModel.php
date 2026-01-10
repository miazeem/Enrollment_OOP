<?php
/**
 * BaseModel Abstract Class
 * Provides common CRUD operations for all model classes
 * Implements abstraction to reduce code duplication
 */

require_once __DIR__ . '/../config/Database.php';

abstract class BaseModel {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all records from the table
     * @return array
     */
    public function getAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get record by ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Delete record by ID
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Abstract method for creating a new record
     * Must be implemented by child classes
     * @param array $data
     * @return mixed
     */
    abstract public function create($data);

    /**
     * Abstract method for updating a record
     * Must be implemented by child classes
     * @param int $id
     * @param array $data
     * @return bool
     */
    abstract public function update($id, $data);

    /**
     * Get database connection (for child classes that need custom queries)
     * @return PDO
     */
    protected function getDb() {
        return $this->db;
    }

    /**
     * Get table name (for child classes that need it)
     * @return string
     */
    protected function getTable() {
        return $this->table;
    }
}
