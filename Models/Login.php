<?php
require_once '../DAOGeral.php';

class Login
{
    public ?int $Id = null;
    public ?string $Email = null;
    public ?string $Senha = null;
    public ?string $Nome = null;

    private DAOGeral $dao;

    public function __construct()
    {
        $this->dao = new DAOGeral();
    }

    /**
     * Autentica o usuário com base no e-mail e senha.
     */
    public function logar(): ?array
    {
        $tabela = "Usuarios"; // Nome da tabela no banco de dados
        $rotulos = "*";
        $condicao = "Email = ? AND Senha = ?";
        $valores = [$this->Email, $this->Senha];

        $result = $this->dao->consultar($tabela, $rotulos, $condicao, $valores);
        return $result ? $result[0] : null;
    }
}
