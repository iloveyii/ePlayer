<?php

namespace App\Models;

final class Database
{
    private static $instance;
    public $db;
    public $numRows;
    public $lastId;

    /**
     * @return Database
     * @throws \Exception
     */
    public static function connect()
    {
        $db = null;

        if (isset(self::$instance)) {
            return self::$instance;
        }

        try {
            $connectionString = sprintf("mysql:host=%s;dbname=%s;charset=utf8;", DB_HOST, DB_NAME);
            $db = new \PDO($connectionString, DB_USER, DB_PASS);
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            Log::write('Database connected successfully', INFO);
        } catch (exception $e) {
            throw new Exception($e->getMessage());
        }

        self::$instance = new self();
        self::$instance->db = $db;

        return self::$instance;
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * @param string $query select sql statement
     * @param array $params associative array of params
     * @return mixed array of rows
     */
    public function selectAll($query, $params = [])
    {
        try {
            $sth = $this->db->prepare($query);
            $sth->execute($params);
            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
            $this->lastId = $this->db->lastInsertId();
            $this->numRows = count($result) - 1;
            return $result;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage() + $query);
        }
    }

    public function selectOne($query, $params)
    {
        try {
            $sth = $this->db->prepare($query);
            $sth->execute($params);
            $result = $sth->fetch(\PDO::FETCH_ASSOC);
            $this->lastId = $this->db->lastInsertId();
            $this->numRows = count((array)$result);
            return $result;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage() + $query);
        }
    }

    public function delete($query, $params)
    {
        try {
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute($params);
            return $result;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage() + $query);
        }
    }

    public function update($query, $params)
    {
        try {
            $stmt = $this->db->prepare($query);
            $num = $stmt->execute($params);
            $this->numRows = $num;
            return $num;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage() + $query);
        } catch (Exception $e) {
            throw new Exception($e->getMessage() + $query);
        }
    }

    public function insert($query, $params = [])
    {
        self::connect();

        try {
            $sth = $this->db->prepare($query);
            $sth->execute($params);
            $this->lastId = $this->db->lastInsertId();
            return $this->lastId;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage() + $query);
        } catch (Exception $e) {
            throw new Exception($e->getMessage() + $query);
        }
    }

    public function exec($query)
    {
        try {
            $num = $this->db->exec($query);
            return $num;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage() + $query);
        } catch (Exception $e) {
            throw new Exception($e->getMessage() + $query);
        }
    }

}

