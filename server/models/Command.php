<?php

namespace App\Models;


class Command extends Model
{
    /**
     * @var null|int
     */
    public $id;
    public $cmd;
    public $status;

    /**
     * @var string
     */
    public $tableName = 'command';


    /**
     * Post constructor.
     */
    public function __construct()
    {
    }

    /**
     * Pass request object to this method to set the object attributes
     * @param array $attributes
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->id = isset($attributes['id'])  ? $attributes['id'] : null;
        $this->cmd = isset($attributes['cmd']) ? $attributes['cmd'] : null;
        $this->status = $attributes['status'];
        $this->isNewRecord = $this->id === null ? true : false;
        return $this;
    }

    /**
     * These are the validation rules for the attributes
     * @return array
     */
    public function rules() : array
    {
        return [
            'id' => ['integer'],
            'cmd' => ['string', 'minLength'=>5, 'maxLength'=>40],
            'status' => ['integer'],
        ];
    }

    // Abstract methods

    public function createTable(): bool
    {
        $createTable = "CREATE TABLE $this->tableName(
        id INT( 11 ) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        cmd varchar( 40 ),
        status INT( 11 ),
        createdAt DATETIME DEFAULT CURRENT_TIMESTAMP
        );";
        $result = Database::connect()->exec($createTable);
        Log::write("Created table $this->tableName", INFO);
        return $result;
    }

    // CRUD

    /**
     * Creates a db post record
     * @return bool
     * @throws \Exception
     */
    public function create()
    {
        $query = sprintf("INSERT INTO %s (cmd, status) 
                                 VALUES (:cmd, :status)
                                 ON DUPLICATE KEY UPDATE cmd=:cmd, status=:status
                                 ", $this->tableName);
        $params = [':cmd'=>$this->cmd, ':status'=>$this->status];
        return Database::connect()->insert($query, $params);
    }

    /**
     * Reads all posts from db into associative array
     * @param null | integer $id
     * @return array
     * @throws \Exception
     */
    public function read( $id = null)
    {
        $query = sprintf("SELECT * FROM %s", $this->tableName);
        $params = [];

        if($id !== null) {
            $query = sprintf("SELECT * FROM %s WHERE id=:id", $this->tableName);
            $params = [':id'=>$id];
        }
        $rows = Database::connect()->selectAll($query, $params);

        return $rows;
    }

    public function readColumnAll($columnName)
    {
        $query = sprintf("SELECT %s FROM %s", $columnName, $this->tableName);
        $rows = Database::connect()->selectAll($query, []);
        return $rows;
    }

    /**
     * Updates the given record in DB using id in Request object
     * @return bool
     * @throws \Exception
     */
    public function update()
    {
        $query = sprintf("UPDATE %s SET title=:title, description=:description WHERE id=:id", $this->tableName);
        $params = [':id'=>$this->id, ':title'=>$this->title, ':description'=>$this->description];
        $result = Database::connect()->update($query, $params);
        return $result;
    }

    public function setStatus()
    {
        $query = sprintf("UPDATE %s SET status=:status WHERE id=:id", $this->tableName);
        $params = [':id'=>$this->id, ':status'=>$this->status];
        $result = Database::connect()->update($query, $params);
        return $result;
    }
}
