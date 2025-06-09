<?php
require_once '../DAO/DAOGeral.php';
require_once '../Helpers/Erros.php';

use SiteLivraria\Helpers\Erros;
use DAOGeral;
use PDOException;

class Livro
{
    use Erros;

    public ?int $Id = null;

    public array $rows_categorias = [];
    public array $rows_autores = [];

    public $Id_Categoria;
    public $Id_Autores = [];

    private ?string $Titulo = null;
    private ?string $Isbn = null;
    private ?string $Editora = null;
    private ?string $Ano = null;

    public array $rows = [];

    private DAOGeral $dao;

    public function __construct()
    {
        $this->dao = new DAOGeral();
    }

    public function setTitulo(string $titulo): void
    {
        if (strlen($titulo) < 3) {
            $this->setError("Título deve ter no mínimo 3 caracteres.");
        } else {
            $this->Titulo = $titulo;
        }
    }

    public function getTitulo(): ?string
    {
        return $this->Titulo;
    }

    public function setIsbn(string $isbn): void
    {
        if (strlen($isbn) < 3) {
            $this->setError("ISBN deve ter no mínimo 3 caracteres.");
        } else {
            $this->Isbn = $isbn;
        }
    }

    public function getIsbn(): ?string
    {
        return $this->Isbn;
    }

    public function setEditora(string $editora): void
    {
        if (strlen($editora) < 3) {
            $this->setError("Editora deve ter no mínimo 3 caracteres.");
        } else {
            $this->Editora = $editora;
        }
    }

    public function getEditora(): ?string
    {
        return $this->Editora;
    }

    public function setAno(string $ano): void
    {
        if (strlen($ano) < 3) {
            $this->setError("Ano deve ter no mínimo 3 caracteres.");
        } else {
            $this->Ano = $ano;
        }
    }

    public function getAno(): ?string
    {
        return $this->Ano;
    }

    public function salvar(): bool
    {
        if (!$this->Titulo) $this->setError("Título é obrigatório.");
        if (!$this->Isbn) $this->setError("ISBN é obrigatório.");
        if (!$this->Editora) $this->setError("Editora é obrigatória.");
        if (!$this->Ano) $this->setError("Ano é obrigatório.");
        if (!$this->Id_Categoria) $this->setError("Categoria é obrigatória.");
        if (!is_array($this->Id_Autores) || count($this->Id_Autores) === 0) {
            $this->setError("Pelo menos um autor deve ser selecionado.");
        }

        if ($this->hasErrors()) return false;

        try {
            return $this->dao->inserir(
                "Livros",
                "Titulo, Isbn, Editora, Ano, Id_Categoria",
                [
                    $this->Titulo,
                    $this->Isbn,
                    $this->Editora,
                    $this->Ano,
                    $this->Id_Categoria
                ]
            );
        } catch (PDOException $e) {
            $this->setError("Erro ao salvar livro: " . $e->getMessage());
            return false;
        }
    }

    public function pesquisarPorId(int $id): ?array
    {
        try {
            $result = $this->dao->consultar("Livros", "*", "Id = ?", [$id]);
            return $result ? $result[0] : null;
        } catch (PDOException $e) {
            $this->setError("Erro ao buscar livro: " . $e->getMessage());
            return null;
        }
    }

    public function pesquisarTudo(): array
    {
        try {
            $this->rows = $this->dao->consultar("Livros", "*", "1");
            return $this->rows;
        } catch (PDOException $e) {
            $this->setError("Erro ao buscar livros: " . $e->getMessage());
            return [];
        }
    }

    public function deletar(int $id): bool
    {
        try {
            return $this->dao->deletar("Livros", "Id = ?", [$id]);
        } catch (PDOException $e) {
            $this->setError("Erro ao excluir livro: " . $e->getMessage());
            return false;
        }
    }
}
