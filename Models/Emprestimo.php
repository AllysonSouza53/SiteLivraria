<?php
require_once '../DAOGeral.php';
require_once '../Helpers/Erros.php';

use SiteLivraria\Helpers\Erros;
use DAOGeral;
use PDOException;

class Emprestimo
{
    use Erros;

    public ?int $Id = null;
    public ?int $Id_Usuario = null;
    public ?int $Id_Livro = null;
    public ?int $Id_Aluno = null;

    private ?string $DataEmprestimo = null;
    private ?string $DataDevolucao = null;

    public ?Aluno $Dados_Aluno = null;
    public ?Livro $Dados_Livro = null;

    public array $rows_livros = [];
    public array $rows_alunos = [];
    public array $rows = [];

    private DAOGeral $dao;

    public function __construct()
    {
        $this->dao = new DAOGeral();
    }

    public function setDataEmprestimo(string $data): void
    {
        $this->DataEmprestimo = $data;
    }

    public function setDataDevolucao(string $data): void
    {
        $this->DataDevolucao = $data;
    }

    public function getId(): ?int
    {
        return $this->Id;
    }

    public function getDataEmpre(): ?string
    {
        return $this->DataEmprestimo;
    }

    public function getDataDevol(): ?string
    {
        return $this->DataDevolucao;
    }

    public function salvar(): bool
    {
        if (!$this->Id_Usuario) $this->setError("Usuário responsável não informado.");
        if (!$this->Id_Livro) $this->setError("Livro é obrigatório.");
        if (!$this->Id_Aluno) $this->setError("Aluno é obrigatório.");
        if (!$this->DataEmprestimo) $this->setError("Data de empréstimo é obrigatória.");
        if (!$this->DataDevolucao) $this->setError("Data de devolução é obrigatória.");

        if ($this->hasErrors()) {
            return false;
        }

        try {
            if ($this->Id === null) {
                return $this->dao->inserir(
                    "Emprestimos",
                    "Id_Usuario, Id_Livro, Id_Aluno, DataEmprestimo, DataDevolucao",
                    [
                        $this->Id_Usuario,
                        $this->Id_Livro,
                        $this->Id_Aluno,
                        $this->DataEmprestimo,
                        $this->DataDevolucao
                    ]
                );
            } else {
                return $this->dao->alterar(
                    "Emprestimos",
                    "Id_Usuario = ?, Id_Livro = ?, Id_Aluno = ?, DataEmprestimo = ?, DataDevolucao = ?",
                    "Id = ?",
                    [
                        $this->Id_Usuario,
                        $this->Id_Livro,
                        $this->Id_Aluno,
                        $this->DataEmprestimo,
                        $this->DataDevolucao,
                        $this->Id
                    ]
                );
            }
        } catch (PDOException $e) {
            $this->setError("Erro ao salvar empréstimo: " . $e->getMessage());
            return false;
        }
    }

    public function pesquisarPorId(int $id): ?array
    {
        try {
            $result = $this->dao->consultar("Emprestimos", "*", "Id = ?", [$id]);
            return $result ? $result[0] : null;
        } catch (PDOException $e) {
            $this->setError("Erro ao buscar empréstimo: " . $e->getMessage());
            return null;
        }
    }

    public function pesquisarTudo(): array
    {
        try {
            $this->rows = $this->dao->consultar("Emprestimos", "*", "1");
            return $this->rows;
        } catch (PDOException $e) {
            $this->setError("Erro ao buscar empréstimos: " . $e->getMessage());
            return [];
        }
    }

    public function deletar(int $id): bool
    {
        try {
            return $this->dao->deletar("Emprestimos", "Id = ?", [$id]);
        } catch (PDOException $e) {
            $this->setError("Erro ao excluir empréstimo: " . $e->getMessage());
            return false;
        }
    }
}
