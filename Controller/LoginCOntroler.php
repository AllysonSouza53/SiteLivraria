<?php
use SiteLivraria\Models\Login;

final class LoginController
{
    public static function index(): void
    {
        $model = new Login();

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $model->Email = trim($_POST['email'] ?? '');
            $model->Senha = trim($_POST['senha'] ?? '');

            if (!$model->Email || !$model->Senha) {
                $model->setError("Email e senha são obrigatórios.");
            } else {
                $usuario = $model->logar();

                if ($usuario !== null) {
                    $_SESSION['usuario_logado'] = $usuario;

                    // Lembrar o usuário
                    if (isset($_POST['lembrar'])) {
                        setcookie(
                            "sistema_biblioteca_usuario",
                            $usuario['Email'], // ou $usuario->Email, dependendo da estrutura retornada
                            time() + (60 * 60 * 24 * 30),
                            "/"
                        );
                    }

                    header("Location: /");
                    exit;
                } else {
                    $model->setError("Email ou senha incorretos.");
                }
            }
        }

        // Preenche o campo de e-mail com cookie, se existir
        if (isset($_COOKIE['sistema_biblioteca_usuario'])) {
            $model->Email = $_COOKIE['sistema_biblioteca_usuario'];
        }

        include VIEWS . '/Login/form_login.php';
    }

    public static function logout(): void
    {
        session_destroy();
        header("Location: /login");
        exit;
    }

    public static function getUsuario(): Login
    {
        return unserialize(serialize($_SESSION['usuario_logado']));
    }
}
