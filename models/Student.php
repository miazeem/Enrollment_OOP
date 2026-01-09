<?php
/**
 * Student Model Class
 * Handles all student-related database operations
 */

require_once __DIR__ . '/../config/Database.php';

class Student {
    private $db;
    private $table = 'students';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all students
     * @return array
     */
    public function getAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get student by ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Create new student
     * @param array $data
     * @return bool
     */
    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (first_name, last_name, email, phone, date_of_birth, status) 
                  VALUES (:first_name, :last_name, :email, :phone, :date_of_birth, :status)";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':date_of_birth', $data['date_of_birth']);
        $stmt->bindParam(':status', $data['status']);
        
        return $stmt->execute();
    }

    /**
     * Update student
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $query = "UPDATE {$this->table} 
                  SET first_name = :first_name, 
                      last_name = :last_name, 
                      email = :email, 
                      phone = :phone, 
                      date_of_birth = :date_of_birth, 
                      status = :status 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':date_of_birth', $data['date_of_birth']);
        $stmt->bindParam(':status', $data['status']);
        
        return $stmt->execute();
    }

    /**
     * Delete student
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Get student's enrolled courses
     * @param int $studentId
     * @return array
     */
    public function getEnrolledCourses($studentId) {
        $query = "SELECT c.*, e.enrollment_date, e.grade, e.status as enrollment_status
                  FROM courses c
                  INNER JOIN enrollments e ON c.id = e.course_id
                  WHERE e.student_id = :student_id
                  ORDER BY e.enrollment_date DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Search students
     * @param string $search
     * @return array
     */
    public function search($search) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE first_name LIKE :search 
                     OR last_name LIKE :search 
                     OR email LIKE :search
                  ORDER BY student_id DESC";
        
        $stmt = $this->db->prepare($query);
        $searchTerm = "%{$search}%";
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
