<?php

class User {

    /******************************************************/
    /******************************************************/
    /*******               USERS CLASS              *******/
    /*******                                        *******/
    /*******               USER RIGHTS:             *******/
    /*******           0:admin  |  1:user           *******/ 
    /*******                                        *******/
    /*******          Admin user deatail:           *******/
    /******* email:admin@admin.rand  pw: lOgin123   *******/    
    /*******                                        *******/
    /******* function sanitize attributes: escapes  *******/
    /******* characters so no sql injection is pos- *******/
    /******* ssible;                                *******/
    /*******                                        *******/ 
    /*******               ACTIVE:                  *******/
    /*******           0: False, 1:True             *******/
    /*******                                        *******/ 
    /******* instantiate: creates object from array *******/       
    /******************************************************/
    /******************************************************/

    public $usersId;
    public $usersName;
    public $usersEMailAdress;
    public $usersTelNum;
    public $usersBirthDay;
    public $usersPassword;
    public $usersRights;
    public $usersActive;
    

    static protected $database;

    static protected $db_columns = ['usersId','usersName','usersEMailAdress','usersTelNum','usersBirthDay','usersPassword','usersRights','usersActive'];

    public function __construct($args=[]){

        $this->usersName        = $args['usersName']        ?? '';
        $this->usersEMailAdress = $args['usersEMailAdress'] ?? '';
        $this->usersTelNum      = $args['usersTelNum']      ?? '';
        $this->usersBirthDay    = $args['usersBirthDay']    ?? '';
        $this->usersPassword    = $args['usersPassword']    ?? '';
        $this->usersRights      = $args['usersRights']      ?? 1;
        $this->usersActive      = $args['usersActive']      ?? 1;   
    }

    static public function set_database($database){
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
            exit('Valami baj van ay sql-lel');
        $result->free();

        return $object_array;
    }

//--------------------------------------------------------------------------------------------------------------------------------------------

    public function create()
    {
        $attributes = $this->sanitized_attributes();
        $sql = "insert into users (";
        $sql .= join(', ', array_keys($attributes));
        $sql .= ") values ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";

        $result=self::$database->query($sql);

        if($result){
            $this->usersId = self::$database->insert_id;
        }
        return $result;
    }

    public function check_pw_for_reset($id,$pw)
    {
        $sql = "select * from users where usersId = '".self::$database->escape_string($id)."'";

        $result = self::find_by_sql($sql);

        $error = false;
        $errorText = '';

        if(!password_verify($pw,$result[0]->usersPassword))
        {
            $error = true;
            $errorText = "Wrong Password";

            echo 'Wrong Password';
            return 0;
        }

        return 'Request sent';
        
    }

    public function checkLogin($email,$pw)
    {
        $sql = "select * from users where usersEMailAdress = '".self::$database->escape_string($email)."'";

        $result = self::find_by_sql($sql);

        $error = false;
        $errorText = '';

        if(!password_verify($pw,$result[0]->usersPassword))
        {
            $error = true;
            $errorText = "Wrong Password";
        }

        if(!$result[0]->usersActive)
        {
            $error = true;
            $errorText = "Profile has been deactivated";
        }

        if(!$error)
            return $result[0]->usersId;
        else 
            return 'Could not log in: ' . $errorText;
    }

    static public function find_all()
    {
        $sql = "select * from users";

        $res = self::find_by_sql($sql);

        return $res;
    }

    static public function find_by_id($id)
    {
        $sql = "select * from users where usersId = '".self::$database->escape_string($id)."'";

        $result = self::find_by_sql($sql);

        return $result[0];
    }

    static public function find_user_rights($id)
    {
        $sql = "select usersRights from users where usersId = '".self::$database->escape_string($id)."'";

        $result = self::find_by_sql($sql);

        return $result[0];
    }

    public function find_same_email($email)
    {
        $sql = "select * from users where usersEMailAdress = '".self::$database->escape_string($email)."'";

        $result = self::find_by_sql($sql);

        if($result[0]->usersEMailAdress)
            echo $result[0]->usersId;

        else
            echo 'emailFree';
    }

    public function update()
    {
        $attributes = $this->sanitized_attributes();
        $attribute_pairs=[];
        foreach ($attributes as $key => $value){
            $attribute_pairs[]="{$key}='{$value}'";
        }
        $sql="update users set ";
        $sql.=join(', ',$attribute_pairs);
        $sql.=" WHERE usersId = '" . self::$database->escape_string($this->usersId) ."' ";
        $sql.="limit 1";
        $result = self::$database->query($sql);
        return $result;
    }
    
    public function delete()
    {
        $sql="delete from users";
        $sql .=" WHERE usersId='".self::$database->escape_string($this->usersId)."' ";
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

    public function attributes() {
        $attributes = [];
        foreach(self::$db_columns as $column){
            if($column == 'userId') {continue;}
            $attributes[$column] = $this->$column;
        }
        return $attributes;
    }

    protected function sanitized_attributes(){
        $sanitized = [];
        foreach($this->attributes() as $key=>$value){
            $sanitized[$key] = self::$database->escape_string($value);
        }
        return $sanitized;
    }
}

?>