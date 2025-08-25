<?php
class DAOGeral {
    private $host = "localhost";
    private $dbName = "livraria";  
    private $username = "root";
    private $password = "";
    private $conexao;

    // Método para conectar com PDO
    private function Conexao()
    {
        if ($this->conexao === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Lança exceções em erros
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retorna array associativo
                    PDO::ATTR_EMULATE_PREPARES => false, // Usa prepares reais
                ];

                $this->conexao = new PDO($dsn, $this->username, $this->password, $options);
            } catch (PDOException $e) {
                die("Falha na conexão: " . $e->getMessage());
            }
        }

        return $this->conexao;
    }

    public function inserir($tabela, $rotulos, $valores)
    {
        $conexao = $this->Conexao();

        // Criar placeholders ? para prepared statement com base em $valores
        // Supondo que $valores seja um array de valores
        if (!is_array($valores)) {
            die("Valores devem ser um array.");
        }
        
        $placeholders = rtrim(str_repeat('?,', count($valores)), ',');

        $sql = "INSERT INTO $tabela ($rotulos) VALUES ($placeholders)";
        $stmt = $conexao->prepare($sql);

        try {
            $stmt->execute($valores);
        } catch (PDOException $e) {
            die("Erro ao inserir: " . $e->getMessage());
        }
    }

    public function deletar($tabela, $condicao, $valores = [])
    {
        $conexao = $this->Conexao();
        $sql = "DELETE FROM $tabela WHERE $condicao";
        $stmt = $conexao->prepare($sql);

        try {
            $stmt->execute($valores);
        } catch (PDOException $e) {
            die("Erro ao deletar: " . $e->getMessage());
        }
    }

    public function alterar($tabela, $rotulos, $condicao, $valores = [])
    {
        $conexao = $this->Conexao();

        // $valores esperado no formato "campo1 = ?, campo2 = ?"
        $sql = "UPDATE $tabela SET $rotulos WHERE $condicao";
        $stmt = $conexao->prepare($sql);

        try {
            $stmt->execute($valores);
        } catch (PDOException $e) {
            die("Erro ao alterar: " . $e->getMessage());
        }
    }

    public function consultar($tabela, $rotulos, $condicao = "1", $valores = [])
    {
        $conexao = $this->Conexao();
        $sql = "SELECT $rotulos FROM $tabela WHERE $condicao";
        $stmt = $conexao->prepare($sql);

        try {
            $stmt->execute($valores);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Erro ao consultar: " . $e->getMessage());
        }
    }
}
?>

