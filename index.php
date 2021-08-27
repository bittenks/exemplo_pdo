<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRUD exemplo com PDO</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <?php
  require_once 'classeagenda.php';
  $agenda = new Agenda("localhost", "root", "", "aula");
  ?>
  <section id='esquerda'>
    <?php
    if (isset($_GET['id_alterar'])) {
      $id_alterar = addslashes($_GET['id_alterar']);
      $res = $agenda->buscarDadosId($id_alterar);
    }

    if (isset($_POST['nome'])) {
      if (isset($_GET['id_alterar']) && !empty($id_alterar)) {
        $id_up = addslashes($_GET['id_alterar']);
        $nome = addslashes($_POST['nome']);
        $telefone = addslashes($_POST['telefone']);
        $email = addslashes($_POST['email']);
        if (!empty($nome) && !empty($telefone) && !empty($email)) {
          $agenda->alterarDados($id_up, $nome, $telefone, $email);
          header('location:index.php');
        } 
        else 
        {
        ?>
          <div class='aviso'><img src='aviso.png' style="width: 50px">
            <h4>preencha todos os campos</h4>
          </div>
        <?php
        }
      } else {
        $nome = addslashes($_POST['nome']);
        $telefone = addslashes($_POST['telefone']);
        $email = addslashes($_POST['email']);
        if (!empty($nome) && !empty($telefone) && !empty($email)) {
          if (!$agenda->inserirDados($nome,$email,$telefone)) {
            echo "E-mail ou telefone Ja cadastrado.";
          }
        } 
        else 
        {
        ?>
          <div class='aviso'>
            <img src='aviso.png' style="width: 50px">
            <h4>preencha todos os campos</h4>
          </div>
        <?php
        }
      }
    }
    ?>
    <form method="POST">
      <h2>Cadastrar Pessoa</h2>
      <label for="nome">Nome</label>
      <input type="text" name='nome' id='nome' 
      value="<?PHP if (isset($res)){
      echo $res['nome'];} ?>">
      <label for="telefone">Telefone</label>
      <input type="text" name='telefone' id='telefone'
      value="<?PHP if (isset($res)) {
      echo $res['telefone'];} ?>">
      <label for="email">Email</label>
      <input type="email" name='email' id='email' 
      value="<?PHP if (isset($res)) {
      echo $res['email'];} ?>">
      <input type="submit" value='Cadastrar'>
    </form>
  </section>
  <section id="direita">
    <table>
      <tr id='titulo'>
        <td>Nome</td>
        <td>Telefone</td>
        <td colspan=2>Email</td>
      </tr>
      <?php
      $dados = $agenda->buscarDados();
      if (count($dados) > 0) {
        for ($i = 0; $i < count($dados); $i++) {
          echo "<tr>";
          foreach ($dados[$i] as $campo => $valor) {
            if ($campo != "id") {
              echo "<td>" . $valor . "</td>";
            }
          }
          echo "<td><a href=index.php?id_alterar=" . $dados[$i]['id'] . ">Alterar</a> <a href=index.php?id_apagar=" . $dados[$i]['id'] . ">Excluir</a></td>";
          echo "</tr>";
        }
      } else {
        echo 'O banco de dados esta vazio';
      }
      if (isset($_GET['id_apagar'])) {
        $id_apagar = addslashes($_GET['id_apagar']);
        $agenda->apagarDados($id_apagar);
        header('location:index.php');
      }

      ?>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </table>
  </section>
</body>

</html>