<?php

require_once 'D:\www\231026\SiteLivraria\DAO\DAOGeral.php';
require_once 'D:\www\231026\SiteLivraria\Helpers\Erros.php';


class Aluno
{
    use Erros;

    public ?int $Id = null;
    public ?string $Nome = null;
    public ?string $RA = null;
    public ?string $Curso = null;
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

    public function setRA(string $ra): void
    {
        $this->RA = $ra;
    }

    public function setCurso(string $curso): void
    {
        $this->Curso = $curso;
    }

    public function getId(): ?int
    {
        return $this->Id;
    }

    public function getNome(): ?string
    {
        return $this->Nome;
    }

    public function getRA(): ?string
    {
        return $this->RA;
    }

    public function getCurso(): ?string
    {
        return $this->Curso;
    }

    public function salvar(): bool
    {
        if (!$this->Nome || strlen($this->Nome) < 3) {
            $this->setError("Nome deve ter no mínimo 3 caracteres.");
        }

        if (!$this->RA || strlen($this->RA) < 3) {
            $this->setError("RA inválido.");
        }

        if (!$this->Curso || strlen($this->Curso) < 3) {
            $this->setError("Curso inválido.");
        }

        if ($this->hasErrors()) {
            return false;
        }

        try {
            if ($this->Id === null) {
                return $this->dao->inserir("Alunos", "Nome, RA, Curso", [
                    $this->Nome,
                    $this->RA,
                    $this->Curso
                ]);
            } else {
                return $this->dao->alterar("Alunos", "Nome = ?, RA = ?, Curso = ?", "Id = ?", [
                    $this->Nome,
                    $this->RA,
                    $this->Curso,
                    $this->Id
                ]);
            }
        } catch (PDOException $e) {
            $this->setError("Erro ao salvar aluno: " . $e->getMessage());
            return false;
        }
    }

    public function pesquisarPorId(int $id): ?array
    {
        try {
            $result = $this->dao->consultar("Alunos", "*", "Id = ?", [$id]);
            return $result ? $result[0] : null;
        } catch (PDOException $e) {
            $this->setError("Erro ao buscar aluno: " . $e->getMessage());
            return null;
        }
    }

    public function pesquisarTudo(): array
    {
        try {
            $this->rows = $this->dao->consultar("Alunos", "*", "1");
            return $this->rows;
        } catch (PDOException $e) {
            $this->setError("Erro ao listar alunos: " . $e->getMessage());
            return [];
        }
    }

    public function deletar(int $id): bool
    {
        try {
            return $this->dao->deletar("Alunos", "Id = ?", [$id]);
        } catch (PDOException $e) {
            $this->setError("Erro ao excluir aluno: " . $e->getMessage());
            return false;
        }
    }
}
