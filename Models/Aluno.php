<?php
require_once '../DAOGeral.php';

class Aluno
{
    private ?int $id = null;
    private ?string $nome = null;
    private ?string $ra = null;
    private ?string $curso = null;

    private DAOGeral $dao;

    public function __construct()
    {
        $this->dao = new DAOGeral();
    }

    public function setNome(string $nome): void
    {
        if (strlen($nome) < 3) {
            throw new Exception("Nome deve ter no mínimo 3 caracteres.");
        }
        $this->nome = $nome;
    }

    public function setRA(string $ra): void
    {
        $this->ra = $ra;
    }

    public function setCurso(string $curso): void
    {
        $this->curso = $curso;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function getRA(): ?string
    {
        return $this->ra;
    }

    public function getCurso(): ?string
    {
        return $this->curso;
    }

    // Método para salvar o aluno no banco de dados
    public function salvar(): bool
    {
        $tabela = "alunos";  // ajuste conforme o nome real da tabela
        $rotulos = "nome, ra, curso";
        $valores = [$this->nome, $this->ra, $this->curso];

        return $this->dao->inserir($tabela, $rotulos, $valores);
    }

    // Método para pesquisar um aluno por ID
    public function pesquisarPorId(int $id): ?array
    {
        $tabela = "alunos";
        $rotulos = "*";
        $condicao = "id = ?";
        $valores = [$id];

        $result = $this->dao->consultar($tabela, $rotulos, $condicao, $valores);
        return $result ? $result[0] : null;
    }

    // Método para pesquisar todos os alunos
    public function pesquisarTudo(): array
    {
        $tabela = "alunos";
        $rotulos = "*";
        $condicao = "1";  // busca todos os registros

        return $this->dao->consultar($tabela, $rotulos, $condicao);
    }

    // Método para deletar um aluno
    public function deletar(int $id): bool
    {
        $tabela = "alunos";
        $condicao = "id = ?";
        $valores = [$id];

        return $this->dao->deletar($tabela, $condicao, $valores);
    }
}
?>
