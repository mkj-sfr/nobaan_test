<?php
class Database
{
    private $pdo,
            $query,
            $error = false,
            $results,
            $count = 0;


    private function __construct ()
    {
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=nobaan_test', 'root', ''); 
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    public function query($sql, array $params) 
    {
        $this->error = false;
        if($this->query = $this->pdo->prepare($sql))
        {
            $x = 1;
            if(count($params))
            {
                foreach($params as $param)
                {
                    $this->query->bindValue($x, $param);
                    $x++;
                }
            }

            if($this->query->execute())
            {
                $this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
                $this->count = $this->query->rowCount();
            } else
            {
                $this->error = true;
            }
        }
    }

    public function action($action, $table, array $where)
    {
        if(count($where) === 3)
        {
            $operators = array('=', '>', '<', '>=', '<=');
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if(in_array($operator, $operators))
            {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

                if(!$this->query($sql, array($value))->error())
                {
                    return $this;
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
        if(count($fields))
        {
            $keys = array_keys($fields);
            $values = '';
            $x = 1;

            foreach($fields as $field)
            {
                $values .= '?';

                if($x < count($fields))
                {
                    $values .= ', ';
                }

                $x++;
            }

            $sql = "INSERT INTO {$table} (".implode(', ', $keys).") VALUES ({$values})";

            if(!$this->query($sql, $fields)->error())
            {
                return true;
            }
        }

        return false;
    }

    public function update($table, $id, $fields)
    {
        $set    = '';
        $x      = 1;

        foreach ($fields as $name => $value)
        {
            $set .= "{$name} = ?";

            if ($x < count($fields))
            {
                $set .= ', ';
            }

            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE uid = {$id}";

        if (!$this->query($sql, $fields)->error())
        {
            return true;
        }

        return false;
    }

    public function results()
    {
        return $this->results;
    }

    public function first()
    {
        return $this->results()[0];
    }

    public function count()
    {
        return $this->count;
    }
}