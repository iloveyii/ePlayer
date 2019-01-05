<?php

namespace App\Models;


class Channel extends Model
{
    /**
     * @var null|int
     */
    public $id;
    public $category_id;
    public $name;
    public $url;

    /**
     * @var string
     */
    public $tableName = 'channel';


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
        $this->category_id = isset($attributes['category_id']) ? $attributes['category_id'] : null;
        $this->name = $attributes['name'];
        $this->url = $attributes['url'];
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
            'name' => ['string', 'minLength'=>5, 'maxLength'=>40],
            'url' => ['string', 'minLength'=>5, 'maxLength'=>200],
        ];
    }

    // Abstract methods

    public function createTable(): bool
    {
        $createTable = "CREATE TABLE $this->tableName(
        id INT( 11 ) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        category_id INT( 11 ) UNSIGNED,
        name varchar( 40 ),
        url varchar( 200 ),
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
        $query = sprintf("INSERT INTO %s (category_id, name, url) 
                                 VALUES (null, :name, :url)
                                 ON DUPLICATE KEY UPDATE name=:name, url=:url
                                 ", $this->tableName);
        $params = [':name'=>$this->name, ':url'=>$this->url];
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

}
