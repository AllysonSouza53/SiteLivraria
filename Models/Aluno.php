<?php
    class Aluno
    {
        private ?int $id = null;
        private ?string $nome = null;
        private ?string $ra = null;
        private ?string $curso = null;
        
        public function SetNome(string $nome):void
        {
            if (strlen($nome) < 3) {
                throw new Exception("Nome deve ter no mínimo 3 caracteres.");
            }
            $this->nome = $nome;
        }

        public function SetRA(string $RA):void
        {
            $this->ra = $RA;
        }

        public function SetCurso(string $Curso):void
        {
            $this->curso = $Curso;
        }

        public function GetNome():?string
        {
            return $this->nome;
        }

        public function GetRA():?string
        {
            return $this->ra;
        }

        public function GetCurso():?string
        {
            return $this->curso;
        }

        public function SalvarAluno():Aluno
        {
            return (new DaoGeral())->inserir();
        }
    }
?>