<?php require('head.php');
$id = mysql_real_escape_string($_REQUEST['exerc']);
$X = new Exercicio($user, $id);
?>
<h2>Exerc&iacute;cios de leitura e manipula&ccedil;&atilde;o de dados</h2>
<h3><?php echo $X->nome(); ?></h3>
<?php 
echo $X->html();
?>

<form name="notaR" action="#" method="post" enctype="multipart/form-data">
<input type="hidden" name="exerc" value="<?php echo $X->getId(); ?>">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
<input type="file" name="rfile" id="rfile" accept="text/*">
<button type="submit" value="Submit">OK</button>
</form>

<div id="corretoR" >
<?php 
if (isset($_POST['exerc'])) {
	require_once 'Rserve.php';

	if (empty($_FILES['rfile']["tmp_name"])) { 
		echo "Nenhum arquivo recebido. Verifique se houve algum problema no upload.";
		echo "<br>Poss&iacute;veis causas de erro: <ul><li>Voc&ecirc; esqueceu de fornecer um nome de arquivo?</li>";
		echo "<li>O arquivo &eacute; grande demais? (m&aacute;ximo aceito: 15 mil caracteres)</li>";
		echo "<li>Voc&ecirc; salvou o arquivo usando algum processador de texto, como o Word?</li>";
		echo "</ul>";
	} else {
		$uploadfile = $basedir ."/tmp/".  basename($_FILES['rfile']['tmp_name']);
		move_uploaded_file($_FILES['rfile']['tmp_name'], $uploadfile);

		$r = new Rserve_Connection(RSERVE_HOST);
		$x = $r->evalString('source("'.$basedir.'/corretor.R");');
		$x = $r->evalString('notaR("'.$user->getLogin().'", '.$X->getId().', "'.$uploadfile.'")');   
		echo $x;
	}
}
else 
{ echo "<p>Insira sua resposta no campo acima e aperte OK</p>";
}
?>
</div>
<a href="index.php">In&iacute;cio</a>

</div>
</body>
</html>
