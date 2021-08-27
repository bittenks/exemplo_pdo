<?php
class Agenda{
    private $pdo;


    public function __construct($host, $usuario, $senha, $banco)
    {
        try{
            $this-> pdo = new PDO("mysql:host=" . $host . ";dbname=" . $banco,$usuario,$senha );
        }
        catch (PDOException $e){
            echo "Erro com o banco de dados: " . $e->getMessage();
            exit();
        }
        catch (Exception $e){
            echo "Erro genÃ©rico: " . $e->getMessage();
            exit();
        }
    }
    public function buscarDados()
    {
        $res = array();
        $cmd = $this->pdo->query("SELECT * FROM `agenda` ORDER BY `nome`");
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    public function buscarDadosId($id)
    {
        $res = array();
        $cmd = $this->pdo->query("SELECT * from 'agenda' where 'id' = :id");
        $cmd->bindValue(":id", $id);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    public function inserirDados($nome, $email, $telefone)
    {
        $cmd = $this->pdo->prepare("SELECT id from agenda where email = :em or telefone = :tl");
        $cmd->bindValue(":em", $email);
        $cmd->bindValue(":tl", $telefone);
        $cmd->execute();
        if($cmd->rowCount()>= 0)
        {
            return false;
        }
        else{
            $cmd = $this->pdo->prepare("INSERT INTO agenda (`id`, `nome`, `telefone`, `email`) VALUES ('',:nm, :tl, :em)");
            $cmd->bindValue(":nm", $nome);
            $cmd->bindValue(":em", $email);
            $cmd->bindValue(":tl", $telefone);
            $cmd->execute();
            return true;
        }
    }
    public function apagarDados($id)
    {
        $cmd = $this->pdo->prepare("DELETE FROM `agenda` WHERE `agenda`.`id` = :id");
        $cmd->bindValue(":id", $id);
        $cmd->execute();
    }
    

    public function alterarDados($id, $nome, $telefone, $email)
    {
        $cmd = $this->pdo->prepare("UPDATE agenda SET  'nome' = :nm, 'telefone' = :tl, `email` = :em WHERE 'id' = :id;");
        $cmd->bindValue(":id", $id);
        $cmd->bindValue(":nm", $nome);
        $cmd->bindValue(":em", $email);
        $cmd->bindValue(":tl", $telefone);
        $cmd->execute();
    }
}
?>