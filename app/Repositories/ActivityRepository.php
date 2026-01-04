<?php
namespace App\Repositories;

use App\Models\ActivityModel;

final class ActivityRepository extends BaseRepository
{
    /**
     * @return ActivityModel[]
     */
    public function findAll(): array
    {
        $rows = $this->db->fetch("SELECT * FROM activities_tbl");
        return array_map([ActivityModel::class, 'map'], $rows);
    }

    public function findById(int $id): ?ActivityModel
    {
        $rows = $this->db->fetch("SELECT * FROM activities_tbl WHERE id = ?", $id);
        if (empty($rows)) {
            return null;
        }
        return ActivityModel::map($rows[0]);
    }

    public function findByClassId(int $classId): array
    {
        $rows = $this->db->fetch("SELECT * FROM activities_tbl WHERE class_id = ?", $classId);
        return array_map([ActivityModel::class, 'map'], $rows);
    }

    public function create(array $data): ActivityModel
    {
        $insertId = $this->db->execute(
            "INSERT INTO activities_tbl (class_id, type_id, created_by, name, maximum_score) VALUES (?, ?, ?, ?, ?)",
            $data['classId'],
            $data['typeId'],
            $data['createdBy'],
            $data['name'],
            $data['maximumScore']
        );
        return $this->findById($insertId);
    }

    public function update(int $id, array $data): bool
    {
        $this->db->execute(
            "UPDATE activities_tbl SET class_id = ?, type_id = ?, created_by = ?, name = ?, maximum_score = ? WHERE id = ?",
            $data['classId'],
            $data['typeId'],
            $data['createdBy'],
            $data['name'],
            $data['maximumScore'],
            $id
        );
        return true;
    }

    public function delete(int $id): bool
    {
        $this->db->execute("DELETE FROM activities_tbl WHERE id = ?", $id);
        return true;
    }
}