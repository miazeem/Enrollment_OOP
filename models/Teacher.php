<?php
/**
 * Teacher Model Class
 * Handles all teacher-related database operations
 */

require_once __DIR__ . '/BaseModel.php';

class Teacher extends BaseModel {

    public function __construct() {
        parent::__construct();
        $this->table = 'teachers';
    }

    /**
     * Get active teachers only
     * @return array
     */
    public function getActive() {
        $query = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY first_name, last_name";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Create new teacher
     * @param array $data
     * @return bool
     */
    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (first_name, last_name, email, phone, specialization, hire_date, status) 
                  VALUES (:first_name, :last_name, :email, :phone, :specialization, :hire_date, :status)";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':first_name', $data['first_name']);
        $stmt->bindValue(':last_name', $data['last_name']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':phone', $data['phone']);
        $stmt->bindValue(':specialization', $data['specialization']);
        $stmt->bindValue(':hire_date', $data['hire_date']);
        $stmt->bindValue(':status', $data['status']);
        
        return $stmt->execute();
    }

    /**
     * Update teacher
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
                      specialization = :specialization, 
                      hire_date = :hire_date, 
                      status = :status 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':first_name', $data['first_name']);
        $stmt->bindValue(':last_name', $data['last_name']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':phone', $data['phone']);
        $stmt->bindValue(':specialization', $data['specialization']);
        $stmt->bindValue(':hire_date', $data['hire_date']);
        $stmt->bindValue(':status', $data['status']);
        
        return $stmt->execute();
    }

    /**
     * Get teacher's assigned courses
     * @param int $teacherId
     * @return array
     */
    public function getAssignedCourses($teacherId) {
        $query = "SELECT c.*, ct.assigned_date
                  FROM courses c
                  INNER JOIN course_teachers ct ON c.id = ct.course_id
                  WHERE ct.teacher_id = :teacher_id
                  ORDER BY c.course_code";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':teacher_id', $teacherId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Search teachers
     * @param string $search
     * @return array
     */
    public function search($search) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE first_name LIKE :search 
                     OR last_name LIKE :search 
                     OR email LIKE :search
                     OR specialization LIKE :search
                  ORDER BY teacher_id DESC";
        
        $stmt = $this->db->prepare($query);
        $searchTerm = "%{$search}%";
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
