<?php require('head.php');
$id = mysql_real_escape_string($_REQUEST['exerc']);
$X = new Exercicio($user, $id);
?>
<h2>Exerc&iacute;cios de leitura e manipula&ccedil;&atilde;o de dados</h2>
<h3><?php echo $X->nome(); ?></h3>
<?php 
echo $X->html();
?>

<form name="notaR" action="#" method="post">
<input type="hidden" name="exerc" value="<?php echo $X->getId(); ?>">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
<input type="text" name="texto" id="corrInput" value="<?php if (isset($_POST['texto'])) echo $_POST['texto']; ?>">
<!--/textarea-->
<!--textarea rows=8 cols=70 name="texto"-->
<!--?php if (isset($_POST['texto'])) echo $_POST['texto']; ?-->
<!--/textarea-->
<input type="file" name="rfile" id="rfile" accept="text/*">
<button type="submit" value="Submit">OK</button>
</form>

<div id="corretoR" >
<?php 
if (isset($_POST['exerc'])) {

$user =$user->getLogin();
$exerc=$_POST['exerc'];
$texto=$_POST['texto'];

$rfile = $_FILES['rfile']['tmp_name'];
echo "Nome temporario: $rfile";
require_once 'config.php';
require_once 'Rserve.php';

   $r = new Rserve_Connection(RSERVE_HOST);
   $x = $r->evalString('source("'.$basedir.'/corretor.R");');
   $x = $r->evalString('notaR("'.$user.'", '.$exerc.', "'.$texto.'")');   
   echo $x;
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
