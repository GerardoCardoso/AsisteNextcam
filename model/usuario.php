<?php
    class Usuario{
        private $query;

        public function __construct(){
            $this->query= "";
        }

        public function nombrecompleto_usuario($codigo_persona){
            $this->query = "SELECT concat(nombre,' ',appaterno,' ',amaterno) AS nombre_completo FROM usuarios WHERE codigo_persona='$codigo_persona'";
            return ejecutarConsultaSimpleFila($this->query);
        }
    }
?>