<?php

namespace Nobaan\Backend;

use \PDO;


class Database
{
    private $pdo,
    $query,
    $error = false,
    $results;


    public function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=nobaan_test', 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    public function query($sql, array $params)
    {
        $this->error = false;

        if ($this->query = $this->pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
                foreach ($params as $param) {
                    $this->query->bindValue($x, $param);
                    $x++;
                }
            }

            if ($this->query->execute()) {
                return $this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
            }
            return false;
        }
        return $this->error = $this->query->errorInfo();
    }

    public function action($action, $table, array $where)
    {
        if (count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=');
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

                if ($this->query($sql, array($value))) {
                    return $this->query->fetchAll(PDO::FETCH_OBJ);
                } else {
                    return $this->query->errorInfo();
                }
            }
        }
        return false;
    }

    public function get($table, $where)
    {
        return $this->action('SELECT *', $table, $where);
    }

    public function delete($table, $where)
    {
        return $this->action('DELETE', $table, $where);
    }

    public function insert($table, array $fields)
    {
        if (count($fields)) {
            $keys = array_keys($fields);
            $values = '';
            $x = 1;

            foreach ($fields as $field) {
                $values .= '?';

                if ($x < count($fields)) {
                    $values .= ', ';
                }

                $x++;
            }

            $sql = "INSERT INTO {$table} (" . implode(', ', $keys) . ") VALUES ({$values})";

            if ($this->query($sql, $fields)) 
            {
                return true;
            } else
            {
                return $this->query->errorInfo();
            }
        }

        return false;
    }

    public function update($table, $id, $fields)
    {
        $set = '';
        $x = 1;

        foreach ($fields as $name => $value) {
            $set .= "{$name} = ?";

            if ($x < count($fields)) {
                $set .= ', ';
            }

            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        if ($this->query($sql, $fields)) {
            return true;
        }
        return $this->query->errorInfo();
    }
}