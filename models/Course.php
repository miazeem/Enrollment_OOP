<?php
/**
 * Course Model Class
 * Handles all course-related database operations
 * Supports multiple teachers or no teachers per course
 */

require_once __DIR__ . '/../config/Database.php';

class Course {
    private $db;
    private $table = 'courses';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all courses
     * @return array
     */
    public function getAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get active courses
     * @return array
     */
    public function getActive() {
        $query = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY course_code";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get course by ID
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
     * Create new course
     * @param array $data
     * @return int|false - Returns course_id on success
     */
    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (course_code, course_name, description, credits, semester, academic_year, status) 
                  VALUES (:course_code, :course_name, :description, :credits, :semester, :academic_year, :status)";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':course_code', $data['course_code']);
        $stmt->bindParam(':course_name', $data['course_name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':credits', $data['credits'], PDO::PARAM_INT);
        $stmt->bindParam(':semester', $data['semester']);
        $stmt->bindParam(':academic_year', $data['academic_year']);
        $stmt->bindParam(':status', $data['status']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Update course
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $query = "UPDATE {$this->table} 
                  SET course_code = :course_code, 
                      course_name = :course_name, 
                      description = :description, 
                      credits = :credits, 
                      semester = :semester, 
                      academic_year = :academic_year, 
                      status = :status 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':course_code', $data['course_code']);
        $stmt->bindParam(':course_name', $data['course_name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':credits', $data['credits'], PDO::PARAM_INT);
        $stmt->bindParam(':semester', $data['semester']);
        $stmt->bindParam(':academic_year', $data['academic_year']);
        $stmt->bindParam(':status', $data['status']);
        
        return $stmt->execute();
    }

    /**
     * Delete course
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
     * Assign teachers to a course
     * @param int $courseId
     * @param array $teacherIds - Array of teacher IDs
     * @return bool
     */
    public function assignTeachers($courseId, $teacherIds) {
        try {
            // First, remove existing teachers
            $deleteQuery = "DELETE FROM course_teachers WHERE course_id = :course_id";
            $stmt = $this->db->prepare($deleteQuery);
            $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
            $stmt->execute();

            // Then, add new teachers if any
            if (!empty($teacherIds)) {
                $insertQuery = "INSERT INTO course_teachers (course_id, teacher_id) VALUES (:course_id, :teacher_id)";
                $stmt = $this->db->prepare($insertQuery);
                
                foreach ($teacherIds as $teacherId) {
                    $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
                    $stmt->bindParam(':teacher_id', $teacherId, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
            
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Get teachers assigned to a course
     * @param int $courseId
     * @return array
     */
    public function getTeachers($courseId) {
        $query = "SELECT t.*, ct.assigned_date
                  FROM teachers t
                  INNER JOIN course_teachers ct ON t.id = ct.teacher_id
                  WHERE ct.course_id = :course_id
                  ORDER BY t.first_name, t.last_name";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get teacher IDs assigned to a course
     * @param int $courseId
     * @return array
     */
    public function getTeacherIds($courseId) {
        $query = "SELECT teacher_id FROM course_teachers WHERE course_id = :course_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Get enrolled students for a course
     * @param int $courseId
     * @return array
     */
    public function getEnrolledStudents($courseId) {
        $query = "SELECT s.*, e.enrollment_date, e.grade, e.status as enrollment_status
                  FROM students s
                  INNER JOIN enrollments e ON s.id = e.student_id
                  WHERE e.course_id = :course_id
                  ORDER BY s.last_name, s.first_name";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Search courses
     * @param string $search
     * @return array
     */
    public function search($search) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE course_code LIKE :search 
                     OR course_name LIKE :search 
                     OR description LIKE :search
                  ORDER BY course_id DESC";
        
        $stmt = $this->db->prepare($query);
        $searchTerm = "%{$search}%";
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
