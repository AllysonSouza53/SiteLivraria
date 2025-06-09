<?php

use SiteLivraria\Models\{Emprestimo, Aluno, Livro};
use Exception;

final class EmprestimoController extends Controller
{
    public static function index(): void
    {
        parent::isProtected();

        $model = new Emprestimo();

        try {
            $emprestimos = $model->pesquisarTudo();
            $model->rows = $emprestimos;
        } catch (Exception $e) {
            $model->setError("Ocorreu um erro ao buscar os empréstimos:");
            $model->setError($e->getMessage());
        }

        parent::render('Emprestimo/lista_emprestimo.php', $model);
    }

    public static function cadastro(): void
    {
        parent::isProtected();

        $model = new Emprestimo();

        try {
            if (parent::isPost()) {
                $model->Id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
                $model->Id_Aluno = (int)($_POST['id_aluno'] ?? 0);
                $model->Id_Livro = (int)($_POST['id_livro'] ?? 0);
                $model->Id_Usuario = LoginController::getUsuario()->Id;
                $model->setDataEmprestimo($_POST['data_emprestimo'] ?? '');
                $model->setDataDevolucao($_POST['data_devolucao'] ?? '');

                // Validações
                if (!$model->Id_Aluno) $model->setError("Aluno é obrigatório.");
                if (!$model->Id_Livro) $model->setError("Livro é obrigatório.");
                if (!$model->getDataEmpre()) $model->setError("Data de empréstimo é obrigatória.");
                if (!$model->getDataDevol()) $model->setError("Data de devolução é obrigatória.");

                if (!$model->hasErrors()) {
                    $model->salvar();
                    parent::redirect("/emprestimo");
                }

            } elseif (isset($_GET['id'])) {
                $emprestimo = $model->pesquisarPorId((int)$_GET['id']);
                if ($emprestimo) {
                    $model->Id = $emprestimo['Id'];
                    $model->Id_Aluno = $emprestimo['Id_Aluno'];
                    $model->Id_Livro = $emprestimo['Id_Livro'];
                    $model->Id_Usuario = $emprestimo['Id_Usuario'];
                    $model->setDataEmprestimo($emprestimo['DataEmprestimo']);
                    $model->setDataDevolucao($emprestimo['DataDevolucao']);
                } else {
                    $model->setError("Empréstimo não encontrado.");
                }
            }

        } catch (Exception $e) {
            $model->setError("Erro ao processar o empréstimo:");
            $model->setError($e->getMessage());
        }

        // Preencher selects de alunos e livros
        $alunoModel = new Aluno();
        $livroModel = new Livro();
        $model->rows_alunos = $alunoModel->pesquisarTudo();
        $model->rows_livros = $livroModel->pesquisarTudo();

        parent::render('Emprestimo/form_emprestimo.php', $model);
    }

    public static function delete(): void
    {
        parent::isProtected();

        $model = new Emprestimo();

        try {
            if (isset($_GET['id'])) {
                $model->deletar((int)$_GET['id']);
                parent::redirect("/emprestimo");
            } else {
                $model->setError("ID do empréstimo não fornecido.");
            }

        } catch (Exception $e) {
            $model->setError("Ocorreu um erro ao excluir o empréstimo:");
            $model->setError($e->getMessage());
        }

        parent::render('Emprestimo/lista_emprestimo.php', $model);
    }
}
