<?php
    class connect {
        //Propiedades
     private $dbhost = "localhost";
     private $dbuser = "root";
     private $dbpass = "";
     private $dbname = "disorient_2";
    
        //Metodos
        
        //metodo que inicializa la conexion
        Function __construct()
        {
            $this->connect_db();
        }
        
        //metodo para la conexion a bd
     public function connect_db()
        {	
            $con = mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
            
            if 	(mysqli_connect_error())
            {
                die("Fallo la conexion a BD" . mysqli_connect_error().mysqli_connect_errno());
            }
            else {           
                //echo "Conexion exitosa a la base de datos: ".$this->dbname;
                return	$con;
            }
        }
    }

?>