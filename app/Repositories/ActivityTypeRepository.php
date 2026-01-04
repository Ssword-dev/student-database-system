<?php
namespace App\Repositories;

use App\Models\ActivityTypeModel;

final class ActivityTypeRepository extends BaseRepository
{
    /**
     * @return ActivityTypeModel[]
     */
    public function findAll(): array
    {
        $rows = $this->db->fetch("SELECT * FROM activity_types_tbl");
        return array_map([ActivityTypeModel::class, 'map'], $rows);
    }

    public function findById(int $id): ?ActivityTypeModel
    {
        $rows = $this->db->fetch("SELECT * FROM activity_types_tbl WHERE id = ?", $id);
        if (empty($rows)) {
            return null;
        }
        return ActivityTypeModel::map($rows[0]);
    }

    public function findByClassId(int $classId): array
    {
        $rows = $this->db->fetch("SELECT * FROM activity_types_tbl WHERE class_id = ?", $classId);
        return array_map([ActivityTypeModel::class, 'map'], $rows);
    }

    public function create(array $data): ActivityTypeModel
    {
        $insertId = $this->db->execute(
            "INSERT INTO activity_types_tbl (class_id, created_by, name, weight) VALUES (?, ?, ?, ?)",
            $data['classId'],
            $data['createdBy'],
            $data['name'],
            $data['weight']
        );
        return $this->findById($insertId);
    }

    public function update(int $id, array $data): bool
    {
        $this->db->execute(
            "UPDATE activity_types_tbl SET class_id = ?, created_by = ?, name = ?, weight = ? WHERE id = ?",
            $data['classId'],
            $data['createdBy'],
            $data['name'],
            $data['weight'],
            $id
        );
        return true;
    }

    public function delete(int $id): bool
    {
        $this->db->execute("DELETE FROM activity_types_tbl WHERE id = ?", $id);
        return true;
    }
}