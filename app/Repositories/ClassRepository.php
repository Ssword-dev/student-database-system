<?php
namespace App\Repositories;

use App\Models\ClassModel;

final class ClassRepository extends BaseRepository
{
    /**
     * @return ClassModel[]
     */
    public function findAll(): array
    {
        $rows = $this->db->fetch("SELECT * FROM classes_tbl");
        return array_map([ClassModel::class, 'map'], $rows);
    }

    public function findById(int $id): ?ClassModel
    {
        $rows = $this->db->fetch("SELECT * FROM classes_tbl WHERE id = ?", $id);
        if (empty($rows)) {
            return null;
        }
        return ClassModel::map($rows[0]);
    }

    public function findByTeacherId(int $teacherId): array
    {
        $rows = $this->db->fetch("SELECT * FROM classes_tbl WHERE teacher_id = ?", $teacherId);
        return array_map([ClassModel::class, 'map'], $rows);
    }

    public function create(array $data): ClassModel
    {
        $insertId = $this->db->execute(
            "INSERT INTO classes_tbl (teacher_id, created_by, name, course_name, school_year) VALUES (?, ?, ?, ?, ?)",
            $data['teacherId'],
            $data['createdBy'],
            $data['name'],
            $data['courseName'],
            $data['schoolYear']
        );
        return $this->findById($insertId);
    }

    public function update(int $id, array $data): bool
    {
        $this->db->execute(
            "UPDATE classes_tbl SET teacher_id = ?, created_by = ?, name = ?, course_name = ?, school_year = ? WHERE id = ?",
            $data['teacherId'],
            $data['createdBy'],
            $data['name'],
            $data['courseName'],
            $data['schoolYear'],
            $id
        );
        return true;
    }

    public function delete(int $id): bool
    {
        $this->db->execute("DELETE FROM classes_tbl WHERE id = ?", $id);
        return true;
    }
}