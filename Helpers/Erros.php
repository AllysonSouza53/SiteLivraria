<?php

trait ErrorHandler
{
    
    private array $errors = [];

    /**
     * Adiciona uma mensagem de erro à lista.
     *
     * @param string $msg Mensagem a ser registrada.
     */
    public function setError(string $msg): void
    {
        $this->errors[] = $msg;
    }

    /**
     * Retorna os erros como lista HTML formatada.
     *
     * @return string Lista de erros formatada em <ul>.
     */
    public function getErrors(): string
    {
        if (empty($this->errors)) {
            return '';
        }

        $output = "<ul>";
        foreach ($this->errors as $error) {
            $output .= "<li>$error</li>";
        }
        $output .= "</ul>";

        return $output;
    }

    /**
     * Verifica se há erros registrados.
     *
     * @return bool Verdadeiro se houver ao menos um erro.
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Limpa todos os erros registrados.
     */
    public function clearErrors(): void
    {
        $this->errors = [];
    }
}
