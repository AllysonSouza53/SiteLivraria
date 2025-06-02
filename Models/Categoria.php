<?php
require_once '../DAOGeral.php';

class Categoria
{
    private ?int $id = null;
    private ?string $descricao = null;

    private DAOGeral $dao;

    public function __construct()
    {
        $this->dao = new DAOGeral();
    }

    public function setDescricao(string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    // Método para salvar a categoria no banco de dados
    public function salvar(): bool
    {
        $tabela = "categorias";  
        $rotulos = "descricao";
        $valores = [$this->descricao];

        return $this->dao->inserir($tabela, $rotulos, $valores);
    }

    // Método para pesquisar uma categoria por ID
    public function pesquisarPorId(int $id): ?array
    {
        $tabela = "categorias";
        $rotulos = "*";
        $condicao = "id = ?";
        $valores = [$id];

        $result = $this->dao->consultar($tabela, $rotulos, $condicao, $valores);
        return $result ? $result[0] : null;
    }

    // Método para pesquisar todas as categorias
    public function pesquisarTudo(): array
    {
        $tabela = "categorias";
        $rotulos = "*";
        $condicao = "1";  // busca todos os registros

        return $this->dao->consultar($tabela, $rotulos, $condicao);
    }

    // Método para deletar uma categoria
    public function deletar(int $id): bool
    {
        $tabela = "categorias";
        $condicao = "id = ?";
        $valores = [$id];

        return $this->dao->deletar($tabela, $condicao, $valores);
    }
}
?>
