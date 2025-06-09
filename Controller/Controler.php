<?php

abstract class Controller
{
    final protected static function isProtected(): void
    {
        if (!isset($_SESSION['usuario_logado'])) {
            header("Location: /login");
            exit;
        }
    }

    final protected static function render(string $view, ?object $model = null): void
    {
        include VIEWS . "/$view";
    }

    final protected static function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === "POST";
    }

    final protected static function redirect(string $route): void
    {
        header("Location: $route");
        exit;
    }
}
