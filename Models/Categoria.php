<?php
require_once '../DAOGeral.php';
require_once '../Helpers/Erros.php';

use SiteLivraria\Helpers\Erros;
use DAOGeral;
use PDOException;

class Categoria
{
    use Erros;

    public ?int $Id = null;
    public ?string $Descricao = null;
    public array $rows = [];

    private DAOGeral $dao;

    public function __construct()
    {
        $this->dao = new DAOGeral();
    }

    public function setDescricao(string $Descricao): void
    {
        $this->Descricao = $Descricao;
    }

    public function getId(): ?int
    {
        return $this->Id;
    }

    public function getDescricao(): ?string
    {
        return $this->Descricao;
    }

    public function salvar(): bool
    {
        // Validação interna (caso queira reforçar)
        if (!$this->Descricao || strlen($this->Descricao) < 3) {
            $this->setError("A descrição deve conter ao menos 3 caracteres.");
            return false;
        }

        try {
            if ($this->Id === null) {
                // Inserir
                return $this->dao->inserir("Categorias", "Descricao", [$this->Descricao]);
            } else {
                // Atualizar
                $campos = "Descricao = ?";
                $condicao = "Id = ?";
                $valores = [$this->Descricao, $this->Id];

                return $this->dao->alterar("Categorias", $campos, $condicao, $valores);
            }
        } catch (PDOException $e) {
            $this->setError("Erro ao salvar categoria: " . $e->getMessage());
            return false;
        }
    }


    public function pesquisarPorId(int $id): ?array
    {
        try {
            $result = $this->dao->consultar("Categorias", "*", "Id = ?", [$id]);
            return $result ? $result[0] : null;
        } catch (PDOException $e) {
            $this->setError("Erro ao buscar categoria: " . $e->getMessage());
            return null;
        }
    }


    public function pesquisarTudo(): array
    {
        try {
            $this->rows = $this->dao->consultar("Categorias", "*", "1");
            return $this->rows;
        } catch (PDOException $e) {
            $this->setError("Erro ao buscar categorias: " . $e->getMessage());
            return [];
        }
    }


    public function deletar(int $id): bool
    {
        try {
            return $this->dao->deletar("Categorias", "Id = ?", [$id]);
        } catch (PDOException $e) {
            $this->setError("Erro ao excluir categoria: " . $e->getMessage());
            return false;
        }
    }
}
?>
