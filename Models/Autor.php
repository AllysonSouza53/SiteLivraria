<?php
require_once '../DAOGeral.php';

class Autor
{
    private ?int $id = null;
    private ?string $nome = null;
    private ?string $DataNascimento = null;
    private ?string $CPF = null;

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

    public function setDataNasc(string $DataNascimento): void
    {
        $this->DataNascimento = $DataNascimento;
    }

    public function setCPF(string $CPF): void
    {
        $this->CPF = $CPF;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function getDataNasc(): ?string
    {
        return $this->DataNascimento;
    }

    public function getCPF(): ?string
    {
        return $this->CPF;
    }

    // Método para salvar o aluno no banco de dados
    public function salvar(): bool
    {
        $tabela = "autores";  // ajuste conforme o nome real da tabela
        $rotulos = "nome, datanasc, CPF";
        $valores = [$this->nome, $this->DataNascimento, $this->CPF];

        return $this->dao->inserir($tabela, $rotulos, $valores);
    }

    // Método para pesquisar um aluno por ID
    public function pesquisarPorId(int $id): ?array
    {
        $tabela = "autores";
        $rotulos = "*";
        $condicao = "id = ?";
        $valores = [$id];

        $result = $this->dao->consultar($tabela, $rotulos, $condicao, $valores);
        return $result ? $result[0] : null;
    }

    // Método para pesquisar todos os alunos
    public function pesquisarTudo(): array
    {
        $tabela = "autores";
        $rotulos = "*";
        $condicao = "1";  // busca todos os registros

        return $this->dao->consultar($tabela, $rotulos, $condicao);
    }

    // Método para deletar um aluno
    public function deletar(int $id): bool
    {
        $tabela = "autores";
        $condicao = "id = ?";
        $valores = [$id];

        return $this->dao->deletar($tabela, $condicao, $valores);
    }
}
?>