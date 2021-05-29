<?php
    class Asistencia{
        private $query;

        public function __construct(){
            $this->query= "";
        }

        public function verificarcodigo_persona($codigo_persona){
            $this->query = "SELECT * FROM usuarios WHERE codigo_persona='$codigo_persona'";
            return ejecutarConsultaSimpleFila($this->query);
        }
    
        public function seleccionarcodigo_persona($codigo_persona){
            $this->query = "SELECT tipo, fecha FROM asistencia WHERE codigo_persona = '$codigo_persona' ORDER BY fecha_hora DESC LIMIT 1";
            return ejecutarConsulta($this->query);
        }
    
        public function registrarMovimiento($codigo_persona, $tipo, $fecha, $hora){
            $fech = $fecha.' '.$hora;
            
            $this->query = "INSERT INTO asistencia (codigo_persona, fecha_hora, tipo, fecha) VALUES ('$codigo_persona', '$fech', '$tipo', '$fecha')";
            return ejecutarConsulta($this->query);
        }
    
        public function listar(){
            $this->query="SELECT * FROM asistencia";
            return ejecutarConsulta($this->query);
        }
    }
?>