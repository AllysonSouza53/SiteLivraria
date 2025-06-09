<?php
use SiteLivraria\Models\Categoria;
use Exception;

final class CategoriaController extends Controller
{
    public static function index(): void
    {
        parent::isProtected();

        $model = new Categoria();

        try {
            $categorias = $model->pesquisarTudo();
            $model->rows = $categorias;

        } catch (Exception $e) {
            $model->setError("Ocorreu um erro ao buscar as categorias:");
            $model->setError($e->getMessage());
        }

        parent::render('Categoria/lista_categoria.php', $model);
    }

    public static function cadastro(): void
    {
        parent::isProtected();

        $model = new Categoria();

        try {
            if (parent::isPost()) {
                $model->Id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
                $model->Descricao = trim($_POST['descricao'] ?? '');

                // Validação simples
                if (strlen($model->Descricao) < 3)
                    $model->setError("A descrição da categoria deve ter no mínimo 3 caracteres.");

                if (!$model->hasErrors()) {
                    $model->salvar();
                    parent::redirect("/categoria");
                }

            } elseif (isset($_GET['id'])) {
                $categoria = $model->pesquisarPorId((int)$_GET['id']);
                if ($categoria) {
                    $model->Id = $categoria['Id'];
                    $model->Descricao = $categoria['Descricao'];
                } else {
                    $model->setError("Categoria não encontrada.");
                }
            }

        } catch (Exception $e) {
            $model->setError("Erro ao processar dados da categoria:");
            $model->setError($e->getMessage());
        }

        parent::render('Categoria/form_categoria.php', $model);
    }

    public static function delete(): void
    {
        parent::isProtected();

        $model = new Categoria();

        try {
            if (isset($_GET['id'])) {
                $model->deletar((int)$_GET['id']);
                parent::redirect("/categoria");
            } else {
                $model->setError("ID da categoria não fornecido.");
            }

        } catch (Exception $e) {
            $model->setError("Ocorreu um erro ao excluir a categoria:");
            $model->setError($e->getMessage());
        }

        parent::render('Categoria/lista_categoria.php', $model);
    }
}
