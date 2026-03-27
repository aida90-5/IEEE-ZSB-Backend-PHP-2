<?php
class Database{
    public $connection;
    public $statment;
public function __construct($config,$username='root',$password='aida')){
$dsn = 'mysql:'. http_build_query($config,'',';');
       $this ->connection = new PDO($dsn,'root','aida',[
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
        ]);
    }
}
puplic function query($query ,$params=[]){

    $statment = this->connection->prepare($query);
    $statment -> execute($params);
    return $this ;

}
public function get()
{
    return $this->statment->fetchAll();
}
public  function find()
{
    return this->statment->fetch();

}
public findorfail()
{
$result = $this->find();
    if (!$result) {
        abort();
    }
    return $result;
    }
    }
