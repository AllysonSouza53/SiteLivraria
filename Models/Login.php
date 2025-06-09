<?php
require_once '../DAO/DAOGeral.php';
require_once '../Helpers/Erros.php';

use SiteLivraria\Helpers\Erros;
use PDOException;

class Login
{
    use Erros;

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
     * Retorna o usuário como array se sucesso, ou null se falha.
     */
    public function logar(): ?array
    {
        if (!$this->Email) {
            $this->setError("Email é obrigatório para login.");
            return null;
        }

        if (!$this->Senha) {
            $this->setError("Senha é obrigatória para login.");
            return null;
        }

        try {
            $tabela = "Usuarios";
            $rotulos = "*";
            $condicao = "Email = ? AND Senha = ?";
            $valores = [$this->Email, $this->Senha];

            $result = $this->dao->consultar($tabela, $rotulos, $condicao, $valores);

            if (!$result) {
                $this->setError("Email ou senha inválidos.");
                return null;
            }

            return $result[0];
        } catch (PDOException $e) {
            $this->setError("Erro ao tentar logar: " . $e->getMessage());
            return null;
        }
    }
}

