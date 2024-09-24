<?php
namespace App\Repositories;

use App\Database\DB;
class BaseRepository extends DB
{
    protected string $tableName;

    public function getAllCities(int $id):array{
        $query = $this->select() . "WHERE id_county = $id;";
        $result = $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
        if (!$result) {
            $result = [];
        }

        return $result;
    }
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

    public function delete(int $id)
    {
        $query = "DELETE FROM `{$this->tableName}` WHERE id = $id;";
        $result = $this->mysqli->query($query);
        if (!$result) {
            $result = [];
        }
        return $result;
    }

    public function post(array $data):?int
    {
        $sql = "INSERT INTO `%s`(%s) VALUES (%s);";
        $fields = '';
        $values = '';
        foreach ($data as $field => $value)
        {
            if ($fields > '') {
                $fields .= ','.$field;
            }else
                $fields .= $field;

            if ($values > '') {
                $values .= ','."'$value'";
            }else
                $values .= "'$value'";
        }
        $sql = sprintf($sql, $this->tableName, $fields, $values);
        $this->mysqli->query($sql);
        
        $lastInserted = $this->mysqli->query("SELECT LAST_INSERT_ID() id;")->fetch_assoc();

        return $lastInserted['id'];
    }

    public function put(array $data, int $id):?int
    {
        $sql = "UPDATE `%s` SET %s WHERE id = $id;";
        $set = "";
        foreach ($data as $field => $value)
        {
            if ($set > '') {
                $set .= ", $field = '$value'";
            }else
                $set .= "$field = '$value'";
        }
        $sql = sprintf($sql, $this->tableName, $set);
        $this->mysqli->query($sql);
        return $id;
    }
}
?>