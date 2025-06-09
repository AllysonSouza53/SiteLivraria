<?php
require_once '../DAOGeral.php';

class Livro
{
    public ?int $Id = null;

    public array $rows_categorias = [];
    public array $rows_autores = [];

    public $Id_Categoria;
    public $Id_Autores = [];

    private ?string $Titulo = null;
    private ?string $Isbn = null;
    private ?string $Editora = null;
    private ?string $Ano = null;

    private array $rows = [];

    private DAOGeral $dao;

    public function __construct()
    {
        $this->dao = new DAOGeral();
    }

    // Validações nos setters
    public function setTitulo(string $titulo): void
    {
        if (strlen($titulo) < 3) {
            throw new Exception("Título deve ter no mínimo 3 caracteres.");
        }
        $this->Titulo = $titulo;
    }

    public function getTitulo(): ?string
    {
        return $this->Titulo;
    }

    public function setIsbn(string $isbn): void
    {
        if (strlen($isbn) < 3) {
            throw new Exception("ISBN deve ter no mínimo 3 caracteres.");
        }
        $this->Isbn = $isbn;
    }

    public function getIsbn(): ?string
    {
        return $this->Isbn;
    }

    public function setEditora(string $editora): void
    {
        if (strlen($editora) < 3) {
            throw new Exception("Editora deve ter no mínimo 3 caracteres.");
        }
        $this->Editora = $editora;
    }

    public function getEditora(): ?string
    {
        return $this->Editora;
    }

    public function setAno(string $ano): void
    {
        if (strlen($ano) < 3) {
            throw new Exception("Ano deve ter no mínimo 3 caracteres.");
        }
        $this->Ano = $ano;
    }

    public function getAno(): ?string
    {
        return $this->Ano;
    }

    // Método para salvar o livro
    public function salvar(): bool
    {
        $tabela = "Livros";
        $rotulos = "Titulo, Isbn, Editora, Ano, Id_Categoria";
        $valores = [
            $this->Titulo,
            $this->Isbn,
            $this->Editora,
            $this->Ano,
            $this->Id_Categoria
        ];

        return $this->dao->inserir($tabela, $rotulos, $valores);
    }

    // Método para buscar um livro por ID
    public function pesquisarPorId(int $id): ?array
    {
        $tabela = "Livros";
        $rotulos = "*";
        $condicao = "Id = ?";
        $valores = [$id];

        $result = $this->dao->consultar($tabela, $rotulos, $condicao, $valores);
        return $result ? $result[0] : null;
    }

    // Método para retornar todos os livros
    public function pesquisarTudo(): array
    {
        $tabela = "Livros";
        $rotulos = "*";
        $condicao = "1"; // retorna todos

        $this->rows = $this->dao->consultar($tabela, $rotulos, $condicao);
        return $this->rows;
    }

    // Método para deletar livro
    public function deletar(int $id): bool
    {
        $tabela = "Livros";
        $condicao = "Id = ?";
        $valores = [$id];

        return $this->dao->deletar($tabela, $condicao, $valores);
    }
}
?>
