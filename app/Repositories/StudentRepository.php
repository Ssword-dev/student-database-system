<?php
namespace App\Repositories;

use App\Models\StudentModel;

final class StudentRepository extends BaseRepository
{
    /**
     * @return StudentModel[]
     */
    public function findAll(): array
    {
        $rows = $this->db->fetch("SELECT * FROM students_tbl");
        return array_map([StudentModel::class, 'map'], $rows);
    }

    public function findById(int $id): ?StudentModel
    {
        $rows = $this->db->fetch("SELECT * FROM students_tbl WHERE id = ?", $id);
        if (empty($rows)) {
            return null;
        }
        return StudentModel::map($rows[0]);
    }

    public function findByClassId(int $classId): array
    {
        $rows = $this->db->fetch("SELECT * FROM students_tbl WHERE class_id = ?", $classId);
        return array_map([StudentModel::class, 'map'], $rows);
    }

    public function create(array $data): StudentModel
    {
        $insertId = $this->db->execute(
            "INSERT INTO students_tbl (class_id, created_by, lrn, first_name, last_name, email, contact_number, guardian, guardian_contact_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            $data['classId'],
            $data['createdBy'],
            $data['lrn'],
            $data['firstName'],
            $data['lastName'],
            $data['email'] ?? null,
            $data['contactNumber'] ?? null,
            $data['guardian'] ?? null,
            $data['guardianContactNumber'] ?? null
        );
        return $this->findById($insertId);
    }

    public function update(int $id, array $data): bool
    {
        $this->db->execute(
            "UPDATE students_tbl SET class_id = ?, created_by = ?, lrn = ?, first_name = ?, last_name = ?, email = ?, contact_number = ?, guardian = ?, guardian_contact_number = ? WHERE id = ?",
            $data['classId'],
            $data['createdBy'],
            $data['lrn'],
            $data['firstName'],
            $data['lastName'],
            $data['email'] ?? null,
            $data['contactNumber'] ?? null,
            $data['guardian'] ?? null,
            $data['guardianContactNumber'] ?? null,
            $id
        );
        return true;
    }

    public function delete(int $id): bool
    {
        $this->db->execute("DELETE FROM students_tbl WHERE id = ?", $id);
        return true;
    }
}