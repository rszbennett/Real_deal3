<?php

class Message {

    public $messagesId;
    public $sender;
    public $receiver;
    public $messageText;
    public $seen;
    

    static protected $database;
    static protected $db_columns = ['messagesId','sender','receiver','messageText','seen'];

    public function __construct($args=[]){

        $this->sender      = $args['sender']      ?? '';
        $this->receiver    = $args['receiver']    ?? '';
        $this->messageText = $args['messageText'] ?? '';
        $this->seen        = $args['seen']        ?? '';
    }

    static public function set_database($database)
    {
        self::$database = $database;
    }

    static public function find_by_sql($sql) 
    {
       $result = self::$database->query($sql);

       if(!$result){
           exit("databasa query failed");
       }

        $object_array = [];

        if($result->num_rows > 0)
        {
            while($record = $result->fetch_assoc()){
                $object_array[]= self::instantiate($record);
            }
        }else
            exit(mysqli_error(self::$database));
        $result->free();

        return $object_array;
    }

    //--------------------------------------------------------------------------------------------------------------------------------------------

    static public function find_unseen_for_user($userid)
    {
        $sql = "select count(*) as cnt from messages where receiver = '$userid' && seen = '0'";

        $result = self::$database->query($sql);

        $row = $result->fetch_assoc();

        return $row['cnt'];

    }

    static public function find_by_id($id)
    {
        $sql = "select * from messages where messagesId = '$id'";

        $result = self::find_by_sql($sql);

        return $result;
    }

    static public function get_all_messages()
    {
        $sql = "select * from messages";

        $result = self::find_by_sql($sql);

        return $result;
    }

    static public function get_messages_between_users($id1,$id2)
    {
        $sql = "select * from messages where (sender = '$id1' && receiver = '$id2') || (sender = '$id2' && receiver = '$id1') order by messagesId asc";

        $result = self::find_by_sql($sql);

        return $result;
    }

    static public function get_my_messages($id)
    {
        $sql = "select distinct(sender) as id from messages where receiver = '$id'";

        $result = self::$database->query($sql);
        $senders = [];

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $senders[] = $row['id']; 
            }
        }

        $sql = "select distinct(receiver) as id from messages where sender = '$id'";

        $result = self::$database->query($sql);
        $receiver = [];

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $receiver[] = $row['id']; 
            }
        }

        $messageIds = [];

        foreach($senders as $s)
        {
            $sql = "select max(messagesId) as id from messages where (sender = '$s' && receiver = '$id') || (sender = '$id' && receiver = '$s')";

            $result = self::$database->query($sql);
            $result = $result->fetch_assoc();

            $messageIds[] = $result['id'];
        }

        foreach($receiver as $r)
        {
            $sql = "select max(messagesId) as id from messages where (sender = '$id' && receiver = '$r') || (sender = '$r' && receiver = '$id')";

            $result = self::$database->query($sql);
            $result = $result->fetch_assoc();

            $messageIds[] = $result['id'];
        }

        $messageIds = array_unique($messageIds);

        $sql = "select * from messages where messagesId in ('" . join("','",$messageIds) . "')";

        $result = self::find_by_sql($sql);

        return $result;

    }

    public function create()
    {
        $attributes = $this->sanitized_attributes();
        $sql = "insert into messages (";
        $sql .= join(', ', array_keys($attributes));
        $sql .= ") values ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";
        $result=self::$database->query($sql);
        if($result){
            $this->messagesId = self::$database->insert_id;
        }
        return $result;
    }


    public function update()
    {
        $attributes = $this->sanitized_attributes();
        $attribute_pairs=[];
        foreach ($attributes as $key => $value){
            $attribute_pairs[]="{$key}='{$value}'";
        }
        $sql="update messages set ";
        $sql.=join(', ',$attribute_pairs);
        $sql.=" WHERE messagesId='" . self::$database->escape_string($this->messagesId) ."' ";
        $sql.="limit 1";
        $result = self::$database->query($sql);
        return $result;
    }
    
    public function delete()
    {
        $sql="delete from messages";
        $sql .=" WHERE messagesId = '".self::$database->escape_string($this->messagesId)."' ";
        $sql .= "limit 1";
        $result = self::$database->query($sql);
        return $result;
    }

    static protected function instantiate($record)
    {
        $object = new self;
        foreach($record as $property => $value){
            if(property_exists($object, $property)){
                $object->$property = $value;
            }
        }
        return $object;
    }

    public function merge_attributes($args=[])
    {
        foreach($args as $key => $value){
            if(property_exists($this, $key) && !is_null($value)){
                $this->$key = $value;
            }
        }
    }

    public function attributes() 
    {
        $attributes = [];
        foreach(self::$db_columns as $column){
            if($column == 'messagesId') {continue;}
            $attributes[$column] = $this->$column;
        }
        return $attributes;
    }

    protected function sanitized_attributes()
    {
        $sanitized = [];
        foreach($this->attributes() as $key=>$value){
            $sanitized[$key] = self::$database->escape_string($value);
        }
        return $sanitized;
    }
}

?>