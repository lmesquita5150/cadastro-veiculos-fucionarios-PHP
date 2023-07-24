<?php

$tabela = "funcionario";


$id  = (isset($_POST["id"]))  ? $_POST["id"]  : "";
$acao  = (isset($_POST["acao"])) ? $_POST["acao"]  : "";

$chave_primaria = "id_categoria";

$nome = (isset($_POST["nome"]) && !empty($_POST["nome"])) ? $_POST["nome"] : null;
$datanascimento = (isset($_POST["datanascimento"]) && !empty($_POST["datanascimento"])) ? $_POST["datanascimento"] : null;
$salario = (isset($_POST["salario"]) && !empty($_POST["salario"])) ? $_POST["salario"] : null;


$nome = $senha = $confirm_senha = "";
$nome_err = $senha_err = $confirm_senha_err = "";
 
// Processando dados do formulário quando o formulário é enviado
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validar nome de usuário
    if(empty(trim($_POST["nome"]))){
        $nome_err = "Por favor coloque um nome de usuário.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["nome"]))){
        $nome_err = "O nome de usuário pode conter apenas letras, números e sublinhados.";
    } else{
        // Prepare uma declaração selecionada
        $sql = "SELECT id FROM users WHERE nome = :nome";
        
        if($stmt = $pdo->prepare($sql)){
            // Vincule as variáveis à instrução preparada como parâmetros
            $stmt->bindParam(":nome", $param_nome, PDO::PARAM_STR);
            
            // Definir parâmetros
            $param_nome = trim($_POST["nome"]);
            
            // Tente executar a declaração preparada
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $nome_err = "Este nome de usuário já está em uso.";
                } else{
                    $nome = trim($_POST["nome"]);
                }
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }

            // Fechar declaração
            unset($stmt);
        }
    }
  }   



require_once "../../mysql/funcoes_novo.php";
if ($acao == "listar") {
	try {
    
    $dados = select_crud($tabela);

        echo json_encode(["data"=>$dados]);
	} catch (PDOException $e) {
        echo json_encode(["error"=> $e]);
        die();
	}
};

if ($acao == "editar") {
	try {

    $dados = select_one_crud($tabela, $chave_primaria, $id);

    echo json_encode(["data"=>$dados]);
	} catch (PDOException $e) {
    echo json_encode(["error"=> $e]);
    die();
	}
};


// INCLUSAO
if ($id == 0 || $acao == "inserir") {
	try {
        $arr = ["descricao"=>$descricao,"ativo"=>$ativo,];
    

    $result = insert_crud($tabela, $arr);
    echo $result;
    die();
	} catch (PDOException $e) {
      echo json_encode(["error"=> $e]);
      die();
	}
};


// ALTERACAO
if ($acao == "update") {
  try {

    $arr = ["descricao"=>$descricao,"ativo"=>$ativo,];
    

    $result = update_crud($tabela, $chave_primaria, $id, $arr);
    echo $result;
    die();
  } catch (PDOException $e) { 
    echo json_encode(["error"=> $e]);
    die();
  }  
};


if ($acao == "excluir") {

	try {
   
    $result = delete_crud($tabela, $chave_primaria, $id);

    echo $result;
    die();

	} catch (PDOException $e) {
    echo json_encode(["error"=> $e]);
    die();
    
	}
  
}; 



?>