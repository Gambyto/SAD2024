<?php
    include_once "conec_DB.php";

    class Usera extends connect
    {
        private string $user;
        private string $pass;

        public function __construct($user, $pass) {
            $this->user = $user;
            $this->pass = $pass;
        }

        public function __validate(){
            
            $q = "SELECT * FROM usuario WHERE username='$this->user' AND clave='$this->pass'";
            $r = $this->connect_db()->query($q);

            $nr = $r->num_rows;
            if ($nr == 1) {
                return true;
            } else {
                return false;
            }
        }

        public function __validateprepare($user, $pass){
            $q = "SELECT * FROM usuario WHERE username=? AND clave=?";

            if ($stmt = $this->connect_db()->prepare($q)) {

                $stmt->bind_param("ss", $user, $pass);
                $stmt->execute();
                $result = $stmt->get_result();

                return $result->num_rows === 1;
            } else {
                return false;
            }
        }
    }
    
?>