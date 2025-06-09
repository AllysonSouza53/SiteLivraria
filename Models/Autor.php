<?php

require_once '../DAOGeral.php';
require_once '../Helpers/Erros.php';

use SiteLivraria\Helpers\Erros;
use DAOGeral;
use PDOException;

class Autor
{
    use Erros;

    public ?int $Id = null;
    public ?string $Nome = null;
    public ?string $DataNascimento = null;
    public ?string $CPF = null;

    public array $rows = [];

    private DAOGeral $dao;

    public function __construct()
    {
        $this->dao = new DAOGeral();
    }

    public function setNome(string $nome): void
    {
        $this->Nome = $nome;
    }

    public function setDataNascimento(string $data): void
    {
        $this->DataNascimento = $data;
    }

    public function setCPF(string $cpf): void
    {
        $this->CPF = $cpf;
    }

    public function getId(): ?int
    {
        return $this->Id;
    }

    public function getNome(): ?string
    {
        return $this->Nome;
    }

    public function getDataNascimento(): ?string
    {
        return $this->DataNascimento;
    }

    public function getCPF(): ?string
    {
        return $this->CPF;
    }

    public function salvar(): bool
    {
        if (!$this->Nome || strlen($this->Nome) < 3) {
            $this->setError("Nome deve ter no mínimo 3 caracteres.");
        }

        if (!$this->DataNascimento) {
            $this->setError("Data de nascimento é obrigatória.");
        }

        if (!preg_match('/^\d{11}$/', $this->CPF ?? '')) {
            $this->setError("CPF inválido. Deve conter 11 dígitos numéricos.");
        }

        if ($this->hasErrors()) {
            return false;
        }

        try {
            if ($this->Id === null) {
                return $this->dao->inserir("autores", "nome, datanasc, cpf", [
                    $this->Nome,
                    $this->DataNascimento,
                    $this->CPF
                ]);
            } else {
                return $this->dao->alterar("autores", "nome = ?, datanasc = ?, cpf = ?", "id = ?", [
                    $this->Nome,
                    $this->DataNascimento,
                    $this->CPF,
                    $this->Id
                ]);
            }
        } catch (PDOException $e) {
            $this->setError("Erro ao salvar autor: " . $e->getMessage());
            return false;
        }
    }

    public function pesquisarPorId(int $id): ?array
    {
        try {
            $result = $this->dao->consultar("autores", "*", "id = ?", [$id]);
            return $result ? $result[0] : null;
        } catch (PDOException $e) {
            $this->setError("Erro ao buscar autor: " . $e->getMessage());
            return null;
        }
    }

    public function pesquisarTudo(): array
    {
        try {
            $this->rows = $this->dao->consultar("autores", "*", "1");
            return $this->rows;
        } catch (PDOException $e) {
            $this->setError("Erro ao listar autores: " . $e->getMessage());
            return [];
        }
    }

    public function deletar(int $id): bool
    {
        try {
            return $this->dao->deletar("autores", "id = ?", [$id]);
        } catch (PDOException $e) {
            $this->setError("Erro ao excluir autor: " . $e->getMessage());
            return false;
        }
    }
}
