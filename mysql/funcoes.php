<?php 
function select($sql, $fetchAll = 'all') {
           
    if (strtoupper(substr($sql,0,6)) == "SELECT") {
       require_once "conexao.php";
       $con = Conexao::getInstance();
       $stm = $con->prepare($sql);
       $stm->execute();
         
       if ($fetchAll = 'all') {
          $dados = $stm->fetchAll(PDO::FETCH_ASSOC);
       } else {
          $dados = $stm->fetch(PDO::FETCH_ASSOC);
       }
          $con = null;
          Conexao::disconnect();
          return $dados;
    }
}

function insert($sql) {
           
   if (strtoupper(substr($sql,0,6)) == "INSERT") {
      require_once "conexao.php";
      $con = Conexao::getInstance();
      $stm = $con->prepare($sql);
      $stm->execute();
        
      $con = null;
      Conexao::disconnect();
      return $stm;
   }
}

function update($sql) {
           
   if (strtoupper(substr($sql,0,6)) == "UPDATE") {
      require_once "conexao.php";
      $con = Conexao::getInstance();
      $stm = $con->prepare($sql);
      $stm->execute();
        
      //$con = null;
      //Conexao::disconnect();
     // return $stm;
   }
}

function delete($sql) {
           
   if (strtoupper(substr($sql,0,6)) == "DELETE") {
      require_once "conexao.php";
      $con = Conexao::getInstance();
      $stm = $con->prepare($sql);
      $stm->execute();
        
      $con = null;
      Conexao::disconnect();
      return $stm;
   }
}



function formatMoney($money){
   return str_replace(',', '.', str_replace(".", "",$money));
}

//valorPorExtenso("7.980,50", true, false);
function valorPorExtenso( $valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false ){

   $valor  = floatval(formatMoney($valor));

   $singular = null;
   $plural = null;

   if ( $bolExibirMoeda ){
       $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
       $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
   }
   else{
       $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
       $plural = array("", "", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
   }

   $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
   $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
   $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezessete", "dezoito", "dezenove");
   $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");

   if ( $bolPalavraFeminina ){
   
       if ($valor == 1) {
           $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
       }
       else {
           $u = array("", "um", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
       }
       
       $c = array("", "cem", "duzentas", "trezentas", "quatrocentas","quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");  
       
   }

   $z = 0;

   $valor = number_format( $valor, 2, ".", "." );
   $inteiro = explode( ".", $valor );

   for ( $i = 0; $i < count( $inteiro ); $i++ ) {
       for ( $ii = mb_strlen( $inteiro[$i] ); $ii < 3; $ii++ ) {
           $inteiro[$i] = "0" . $inteiro[$i];
       }
   }

    // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
    $rt = null;
    $fim = count( $inteiro ) - ($inteiro[count( $inteiro ) - 1] > 0 ? 1 : 2);
    for ( $i = 0; $i < count( $inteiro ); $i++ ){
        $valor = $inteiro[$i];
        $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
        $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

        $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
        $t = count( $inteiro ) - 1 - $i;
        $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
        if ( $valor == "000")
            $z++;
        elseif ( $z > 0 )
            $z--;
            
        if ( ($t == 1) && ($z > 0) && ($inteiro[0] > 0) )
            $r .= ( ($z > 1) ? " de " : "") . $plural[$t];
            
        if ( $r )
            $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
    }

    $rt = mb_substr( $rt, 1 );

    return($rt ? trim( $rt ) : "zero");


}

?>