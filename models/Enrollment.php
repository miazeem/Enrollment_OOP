<?php
/**
 * Enrollment Model Class
 * Handles all enrollment-related database operations
 */

require_once __DIR__ . '/../config/Database.php';

class Enrollment {
    private $db;
    private $table = 'enrollments';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all enrollments
     * @return array
     */
    public function getAll() {
        $query = "SELECT e.*, 
                         s.first_name as student_first, s.last_name as student_last,
                         c.course_code, c.course_name
                  FROM {$this->table} e
                  INNER JOIN students s ON e.student_id = s.id
                  INNER JOIN courses c ON e.course_id = c.id
                  ORDER BY e.id DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get enrollment by ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        $query = "SELECT e.*, 
                         s.first_name as student_first, s.last_name as student_last,
                         c.course_code, c.course_name
                  FROM {$this->table} e
                  INNER JOIN students s ON e.student_id = s.id
                  INNER JOIN courses c ON e.course_id = c.id
                  WHERE e.id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Create new enrollment
     * @param array $data
     * @return bool
     */
    public function create($data) {
        // Check if enrollment already exists
        if ($this->checkEnrollmentExists($data['student_id'], $data['course_id'])) {
            return false;
        }

        $query = "INSERT INTO {$this->table} 
                  (student_id, course_id, grade, status) 
                  VALUES (:student_id, :course_id, :grade, :status)";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':student_id', $data['student_id'], PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $data['course_id'], PDO::PARAM_INT);
        $stmt->bindParam(':grade', $data['grade']);
        $stmt->bindParam(':status', $data['status']);
        
        return $stmt->execute();
    }

    /**
     * Update enrollment
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $query = "UPDATE {$this->table} 
                  SET grade = :grade, 
                      status = :status 
                  WHERE enrollment_id = :id";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':grade', $data['grade']);
        $stmt->bindParam(':status', $data['status']);
        
        return $stmt->execute();
    }

    /**
     * Delete enrollment
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
     * Check if enrollment already exists
     * @param int $studentId
     * @param int $courseId
     * @return bool
     */
    public function checkEnrollmentExists($studentId, $courseId) {
        $query = "SELECT COUNT(*) FROM {$this->table} 
                  WHERE student_id = :student_id AND course_id = :course_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Get enrollment statistics
     * @return array
     */
    public function getStatistics() {
        $query = "SELECT 
                    COUNT(DISTINCT student_id) as total_students_enrolled,
                    COUNT(DISTINCT course_id) as total_courses_enrolled,
                    COUNT(*) as total_enrollments,
                    SUM(CASE WHEN status = 'enrolled' THEN 1 ELSE 0 END) as active_enrollments,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_enrollments
                  FROM {$this->table}";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch();
    }
}
