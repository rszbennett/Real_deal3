<?php

class RealEstate {

    /******************************************************/
    /******************************************************/
    /*******                                        *******/
    /*******               ACTIVE:                  *******/
    /*******   0: NOT ACTIVE, 1 ACTIVE              *******/
    /*******                                        *******/
    /*******   CANT BE ACTIVE WHILE REQUEST TRUE    *******/
    /*******                                        *******/
    /*******   CREATED BY IS THE USER ID FROM USERS *******/
    /*******                                        *******/
    /*******               REQUEST:                 *******/  
    /*******           0: FALSE, 1 TRUE             *******/     
    /******************************************************/
    /******************************************************/

    public $realEstateId;
    public $Address;
    public $Cost;  
    public $Bedrooms;
    public $Bathrooms;
    public $SquareMeter;
    public $Active;
    public $Request;
    public $CreatedBy;
    public $CreatedAt;
    

    static protected $database;

    static protected $db_columns = ['realEstateId','Address','Cost','Bedrooms','Bathrooms','SquareMeter','Active','Request','CreatedBy','CreatedAt'];

    public function __construct($args=[]){

        $this->realEstateId = $args['realEstateId'] ?? '';
        $this->Address      = $args['Address']      ?? '';
        $this->Cost         = $args['Cost']         ?? '';
        $this->Bedrooms     = $args['Bedrooms']     ?? '';
        $this->Bathrooms    = $args['Bathrooms']    ?? '';
        $this->SquareMeter  = $args['SquareMeter']  ?? '';
        $this->Active       = $args['Active']       ?? '0';
        $this->Request      = $args['Request']      ?? '1';
        $this->CreatedBy    = $args['CreatedBy']    ?? '';
        $this->CreatedAt    = $args['CreatedAt']    ?? '';
       
    }

    static public function set_database($database){
        self::$database = $database;
    }

    static public function find_by_sql($sql) {
       $result = self::$database->query($sql);

       if(!$result){
           exit("databasa query failed");
       }

        $object_array = [];
        while($record = $result->fetch_assoc()){
          $object_array[]= self::instantiate($record);
        }
        $result->free();

        return $object_array;
    }


    //--------------------------------------------------------------------------------------------------------------------------------------------

    static public function find_by_id($id){
        $sql = "select * from realestate where realEstateId = '".self::$database->escape_string($id)."'";

        $res = self::find_by_sql($sql);

        return $res[0];
    }

    static public function find_all(){
        $sql = "select * from realestate order by CreatedAt desc";

        $res = self::find_by_sql($sql);

        return $res;
    }

    public function update(){
        $attributes = $this->sanitized_attributes();
        $attribute_pairs=[];
        foreach ($attributes as $key => $value){
            if($key == 'CreatedAt') continue;
            $attribute_pairs[]="{$key}='{$value}'";
        }
        $sql="update realestate set ";
        $sql.=join(', ',$attribute_pairs);
        $sql.=" WHERE realEstateId ='" . self::$database->escape_string($this->realEstateId) ."' ";
        $sql.="limit 1";
        $result = self::$database->query($sql);
        return $result;
    }
    
    public function delete(){
        $sql="delete from realestate";
        $sql .=" WHERE realEstateId ='".self::$database->escape_string($this->realEstateId)."' ";
        $sql .= "limit 1";
        $result = self::$database->query($sql);
        return $result;
    }

    public function create(){
        $attributes = $this->sanitized_attributes();
        $sql = "insert into realestate (";
        $sql .= join(', ', array_keys($attributes));
        $sql .= ") values ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";
        $result=self::$database->query($sql);
        if($result){
            $this->realEstateId = self::$database->insert_id;
        }
        return $result;
    }


    static protected function instantiate($record){
        $object = new self;
        foreach($record as $property => $value){
            if(property_exists($object, $property)){
                $object->$property = $value;
            }
        }
        return $object;
    }

    public function merge_attributes($args=[]){
        foreach($args as $key => $value){
            if(property_exists($this, $key) && !is_null($value)){
                $this->$key = $value;
            }
        }
    }

    public function attributes() {
        $attributes = [];
        foreach(self::$db_columns as $column){
            if($column == 'realEstateId') {continue;}
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