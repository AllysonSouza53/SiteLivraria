<?php

use SiteLivraria\Models\{Categoria, Livro, Autor};
use Exception;

final class LivroController extends Controller
{
    public static function index(): void
    {
        parent::isProtected();

        $model = new Livro();

        try {
            $livros = $model->pesquisarTudo();
            $model->rows = $livros;
        } catch (Exception $e) {
            $model->setError("Ocorreu um erro ao buscar os livros:");
            $model->setError($e->getMessage());
        }

        parent::render('Livro/lista_livro.php', $model);
    }

    public static function cadastro(): void
    {
        parent::isProtected();

        $model = new Livro();

        try {
            if (parent::isPost()) {
                $model->Id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
                $model->Titulo = trim($_POST['titulo'] ?? '');
                $model->Id_Categoria = (int)($_POST['id_categoria'] ?? 0);
                $model->Isbn = trim($_POST['isbn'] ?? '');
                $model->Ano = trim($_POST['ano'] ?? '');
                $model->Editora = trim($_POST['editora'] ?? '');
                $model->Id_Autores = $_POST['autor'] ?? [];

                // Validações
                if (strlen($model->Titulo) < 3) $model->setError("Título deve ter no mínimo 3 caracteres.");
                if (strlen($model->Isbn) < 3) $model->setError("ISBN inválido.");
                if (strlen($model->Ano) < 3) $model->setError("Ano inválido.");
                if (strlen($model->Editora) < 3) $model->setError("Editora deve ter no mínimo 3 caracteres.");
                if (!$model->Id_Categoria) $model->setError("Categoria obrigatória.");
                if (!is_array($model->Id_Autores) || count($model->Id_Autores) === 0) {
                    $model->setError("É necessário selecionar pelo menos um autor.");
                }

                if (!$model->hasErrors()) {
                    $model->salvar();
                    parent::redirect("/livro");
                }

            } elseif (isset($_GET['id'])) {
                $livro = $model->pesquisarPorId((int)$_GET['id']);
                if ($livro) {
                    $model->Id = $livro['Id'];
                    $model->Titulo = $livro['Titulo'];
                    $model->Isbn = $livro['Isbn'];
                    $model->Ano = $livro['Ano'];
                    $model->Editora = $livro['Editora'];
                    $model->Id_Categoria = $livro['Id_Categoria'];

                    // Você precisará ajustar isso caso use tabela associativa de livro-autores
                    $model->Id_Autores = []; // Buscar autores do livro via DAO se aplicável
                } else {
                    $model->setError("Livro não encontrado.");
                }
            }

        } catch (Exception $e) {
            $model->setError("Erro ao processar os dados do livro:");
            $model->setError($e->getMessage());
        }

        // Preencher selects
        $model->rows_categorias = (new Categoria())->pesquisarTudo();
        $model->rows_autores = (new Autor())->pesquisarTudo();

        parent::render('Livro/form_livro.php', $model);
    }

    public static function delete(): void
    {
        parent::isProtected();

        $model = new Livro();

        try {
            if (isset($_GET['id'])) {
                $model->deletar((int)$_GET['id']);
                parent::redirect("/livro");
            } else {
                $model->setError("ID do livro não fornecido.");
            }

        } catch (Exception $e) {
            $model->setError("Ocorreu um erro ao excluir o livro:");
            $model->setError($e->getMessage());
        }

        parent::render('Livro/lista_livro.php', $model);
    }
}
