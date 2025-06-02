<?php
require_once '../DAOGeral.php';

class Emprestimo
{
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

    private DAOGeral $dao;

    public function __construct()
    {
        $this->dao = new DAOGeral();
    }

    public function setDataEmprestimo(string $DataEmprestimo): void
    {
        $this->DataEmprestimo = $DataEmprestimo;
    }

    public function setDataDevolucao(string $DataDevolucao): void
    {
        $this->DataDevolucao = $DataDevolucao;
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

    // Método para salvar o empréstimo no banco de dados
    public function salvar(): bool
    {
        $tabela = "Emprestimos";
        $rotulos = "Id_Usuario, Id_Livro, Id_Aluno, DataEmprestimo, DataDevolucao";
        $valores = [
            $this->Id_Usuario,
            $this->Id_Livro,
            $this->Id_Aluno,
            $this->DataEmprestimo,
            $this->DataDevolucao
        ];

        return $this->dao->inserir($tabela, $rotulos, $valores);
    }

    // Método para pesquisar um empréstimo por ID
    public function pesquisarPorId(int $id): ?array
    {
        $tabela = "Emprestimos";
        $rotulos = "*";
        $condicao = "Id = ?";
        $valores = [$id];

        $result = $this->dao->consultar($tabela, $rotulos, $condicao, $valores);
        return $result ? $result[0] : null;
    }

    // Método para pesquisar todos os empréstimos
    public function pesquisarTudo(): array
    {
        $tabela = "Emprestimos";
        $rotulos = "*";
        $condicao = "1";  // busca todos os registros

        return $this->dao->consultar($tabela, $rotulos, $condicao);
    }

    // Método para deletar um empréstimo
    public function deletar(int $id): bool
    {
        $tabela = "Emprestimos";
        $condicao = "Id = ?";
        $valores = [$id];

        return $this->dao->deletar($tabela, $condicao, $valores);
    }
}
?>
