<?php

use SiteLivraria\Models\Autor;
use Exception;

final class AutorController extends Controller
{
    public static function index(): void
    {
        parent::isProtected();

        $model = new Autor();

        try {
            $autores = $model->pesquisarTudo();
            $model->rows = $autores;

        } catch (Exception $e) {
            $model->setError("Ocorreu um erro ao buscar os autores:");
            $model->setError($e->getMessage());
        }

        parent::render('Autor/lista_autor.php', $model);
    }

    public static function cadastro(): void
    {
        parent::isProtected();

        $model = new Autor();

        try {
            if (parent::isPost()) {
                $model->Id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
                $model->Nome = trim($_POST['nome'] ?? '');
                $model->Data_Nascimento = trim($_POST['data_nascimento'] ?? '');
                $model->CPF = trim($_POST['cpf'] ?? '');

                // Validações básicas
                if (strlen($model->Nome) < 3)
                    $model->setError("Nome do autor deve ter no mínimo 3 caracteres.");

                if (empty($model->Data_Nascimento))
                    $model->setError("Data de nascimento é obrigatória.");

                if (!preg_match('/^\d{11}$/', $model->CPF))
                    $model->setError("CPF deve conter 11 dígitos numéricos.");

                if (!$model->hasErrors()) {
                    $model->salvar();
                    parent::redirect("/autor");
                }

            } elseif (isset($_GET['id'])) {
                $autor = $model->pesquisarPorId((int)$_GET['id']);
                if ($autor) {
                    $model->Id = $autor['Id'];
                    $model->Nome = $autor['Nome'];
                    $model->Data_Nascimento = $autor['Data_Nascimento'];
                    $model->CPF = $autor['CPF'];
                } else {
                    $model->setError("Autor não encontrado.");
                }
            }

        } catch (Exception $e) {
            $model->setError("Erro ao processar dados do autor:");
            $model->setError($e->getMessage());
        }

        parent::render('Autor/form_autor.php', $model);
    }

    public static function delete(): void
    {
        parent::isProtected();

        $model = new Autor();

        try {
            if (isset($_GET['id'])) {
                $model->deletar((int)$_GET['id']);
                parent::redirect("/autor");
            } else {
                $model->setError("ID do autor não fornecido.");
            }

        } catch (Exception $e) {
            $model->setError("Ocorreu um erro ao excluir o autor:");
            $model->setError($e->getMessage());
        }

        parent::render('Autor/lista_autor.php', $model);
    }
}
