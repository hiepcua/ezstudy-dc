<?php
class CLS_POSTGRES{
	private $conn=NULL;
	private $rs;
	private $lastid;
	public function __construct(){
		// set value
		$this->HOSTNAME=HOSTNAME;
		$this->USERNAME=DB_USERNAME;
		$this->PASSWORD=DB_PASSWORD;
		$this->PORT=DB_PORT;
		$this->DATANAME=DB_DATANAME;
	}
	private function connect(){
		$conn=@pg_connect("host=".$this->HOSTNAME." port=".$this->PORT." dbname=".$this->DATANAME." user=".$this->USERNAME." password=".$this->PASSWORD."");
		if(!$conn){
			echo "Can't connect PostgreSQL Server!";
			return false;
		}
		$this->conn=$conn;
		return true;
	}
	private function disconnect(){
		if(isset($this->conn))
		return @pg_close($this->conn);
	}
	public function Query($sql){
		if($this->connect()){
			@$rs=pg_query($this->conn,$sql);
			$this->disconnect();
			if($rs){
				$this->rs=$rs;
				return $rs;
			}
		}
		return false;
	}
	public function Exec($sql){
		if($this->connect()){
			@$rs=pg_exec($this->conn,$sql);
			$this->disconnect();
			return $rs;
		}
		return false;
	}
	public function Fetch_Assoc(){
		return (@pg_fetch_assoc($this->rs));
	}
	public function Num_rows() { 
        return(@pg_num_rows($this->rs));
    }
}
?>