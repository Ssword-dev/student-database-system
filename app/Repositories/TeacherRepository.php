<?php
namespace App\Repositories;

use App\Models\TeacherModel;

final class TeacherRepository extends BaseRepository
{
    /**
     * @return TeacherModel[]
     */
    public function findAll(): array
    {
        $rows = $this->db->fetch("SELECT * FROM teachers_tbl");
        return array_map([TeacherModel::class, 'map'], $rows);
    }

    public function findById(int $id): ?TeacherModel
    {
        $rows = $this->db->fetch("SELECT * FROM teachers_tbl WHERE id = ?", $id);
        if (empty($rows)) {
            return null;
        }
        return TeacherModel::map($rows[0]);
    }

    public function findByEmail(string $email): ?TeacherModel
    {
        $rows = $this->db->fetch("SELECT * FROM teachers_tbl WHERE email = ?", $email);
        if (empty($rows)) {
            return null;
        }
        return TeacherModel::map($rows[0]);
    }

    public function create(array $data): TeacherModel
    {
        $insertId = $this->db->execute(
            "INSERT INTO teachers_tbl (first_name, last_name, email, contact_number, address, password_hash) VALUES (?, ?, ?, ?, ?, ?)",
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['contactNumber'] ?? null,
            $data['address'] ?? null,
            $data['passwordHash']
        );
        return $this->findById($insertId);
    }

    public function update(int $id, array $data): bool
    {
        $this->db->execute(
            "UPDATE teachers_tbl SET first_name = ?, last_name = ?, email = ?, contact_number = ?, address = ?, password_hash = ? WHERE id = ?",
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['contactNumber'] ?? null,
            $data['address'] ?? null,
            $data['passwordHash'],
            $id
        );
        return true;
    }

    public function delete(int $id): bool
    {
        $this->db->execute("DELETE FROM teachers_tbl WHERE id = ?", $id);
        return true;
    }
}