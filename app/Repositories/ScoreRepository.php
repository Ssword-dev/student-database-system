<?php
namespace App\Repositories;

use App\Models\ScoreModel;

final class ScoreRepository extends BaseRepository
{
    /**
     * @return ScoreModel[]
     */
    public function findAll(): array
    {
        $rows = $this->db->fetch("SELECT * FROM scores_tbl");
        return array_map([ScoreModel::class, 'map'], $rows);
    }

    public function findById(int $id): ?ScoreModel
    {
        $rows = $this->db->fetch("SELECT * FROM scores_tbl WHERE id = ?", $id);
        if (empty($rows)) {
            return null;
        }
        return ScoreModel::map($rows[0]);
    }

    public function findByActivityId(int $activityId): array
    {
        $rows = $this->db->fetch("SELECT * FROM scores_tbl WHERE activity_id = ?", $activityId);
        return array_map([ScoreModel::class, 'map'], $rows);
    }

    public function findByStudentId(int $studentId): array
    {
        $rows = $this->db->fetch("SELECT * FROM scores_tbl WHERE student_id = ?", $studentId);
        return array_map([ScoreModel::class, 'map'], $rows);
    }

    public function create(array $data): ScoreModel
    {
        $insertId = $this->db->execute(
            "INSERT INTO scores_tbl (activity_id, student_id, created_by, score) VALUES (?, ?, ?, ?)",
            $data['activityId'],
            $data['studentId'],
            $data['createdBy'],
            $data['score']
        );
        return $this->findById($insertId);
    }

    public function update(int $id, array $data): bool
    {
        $this->db->execute(
            "UPDATE scores_tbl SET activity_id = ?, student_id = ?, created_by = ?, score = ? WHERE id = ?",
            $data['activityId'],
            $data['studentId'],
            $data['createdBy'],
            $data['score'],
            $id
        );
        return true;
    }

    public function delete(int $id): bool
    {
        $this->db->execute("DELETE FROM scores_tbl WHERE id = ?", $id);
        return true;
    }
}