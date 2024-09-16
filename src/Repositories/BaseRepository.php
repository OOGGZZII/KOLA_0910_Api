<?php
namespace App\Repositories;

use App\Database\DB;
class BaseRepository extends DB
{
    protected string $tableName;
    public function getAll(): array
    {
        $query = $this->select();
        // $query = $this->select() . "ORDER BY name";
        return $this->mysqli
            ->query($query)->fetch_all(MYSQLI_ASSOC);
    }
    public function select()
    {
        return "SELECT * FROM `{$this->tableName}` ";
    }

    public function find(int $id):array
    {
        $query = $this->select() . "WHERE id = $id";
        $result = $this->mysqli->query($query)->fetch_assoc();
        if (!$result) {
            $result = [];
        }

        return $result;
    }
}
?>