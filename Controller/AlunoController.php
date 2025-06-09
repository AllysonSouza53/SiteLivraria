<?php

namespace App\Controller;

use SiteLivraria\Models\Aluno;
use Exception;

final class AlunoController extends Controller
{
    public static function index(): void
    {
        parent::isProtected();

        $model = new Aluno();

        try {
            $alunos = $model->pesquisarTudo();
            $model->rows = $alunos;

        } catch (Exception $e) {
            $model->setError("Ocorreu um erro ao buscar os alunos:");
            $model->setError($e->getMessage());
        }

        parent::render('Aluno/lista_aluno.php', $model);
    }

    public static function cadastro(): void
    {
        parent::isProtected();

        $model = new Aluno();

        try {
            if (parent::isPost()) {
                $model->Id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
                $model->Nome = trim($_POST['nome'] ?? '');
                $model->RA = trim($_POST['ra'] ?? '');
                $model->Curso = trim($_POST['curso'] ?? '');

                // Validação simples
                if (strlen($model->Nome) < 3) $model->setError("Nome deve conter no mínimo 3 caracteres.");
                if (strlen($model->RA) < 3) $model->setError("RA inválido.");
                if (strlen($model->Curso) < 3) $model->setError("Curso inválido.");

                if (!$model->hasErrors()) {
                    $model->salvar();
                    parent::redirect("/aluno");
                }

            } else if (isset($_GET['id'])) {
                $aluno = $model->pesquisarPorId((int)$_GET['id']);
                if ($aluno) {
                    $model->Id = $aluno['Id'];
                    $model->Nome = $aluno['Nome'];
                    $model->RA = $aluno['RA'];
                    $model->Curso = $aluno['Curso'];
                } else {
                    $model->setError("Aluno não encontrado.");
                }
            }

        } catch (Exception $e) {
            $model->setError("Erro ao processar dados do aluno.");
            $model->setError($e->getMessage());
        }

        parent::render('Aluno/form_aluno.php', $model);
    }

    public static function delete(): void
    {
        parent::isProtected();

        $model = new Aluno();

        try {
            if (isset($_GET['id'])) {
                $model->deletar((int)$_GET['id']);
                parent::redirect("/aluno");
            } else {
                $model->setError("ID do aluno não fornecido.");
            }

        } catch (Exception $e) {
            $model->setError("Ocorreu um erro ao excluir o aluno:");
            $model->setError($e->getMessage());
        }

        parent::render('Aluno/lista_aluno.php', $model);
    }
}
