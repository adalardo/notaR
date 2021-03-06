<?php
require_once("head.php");
### comeca aqui
if (! $USER->admin()) {
	echo "Acesso negado";
	exit;
}
if (isset($_REQUEST['exerc']))
	$X = new Exercicio($_REQUEST['exerc']);
else 
	$X = new Exercicio();

$id = $X->getId();

if (isset($_POST['ntestes'])) {
	$ntestes = $_POST['ntestes'];
} elseif (!empty($id) && $X->maxTeste()) {
	$ntestes = $X->maxTeste();
}else {
	$ntestes = 10;
}

$imp = $X->getProibidos();
if (isset($_POST['nimp'])) {
	$nimp = $_POST['nimp'];
} elseif (!empty($id) && $imp) {
	$nimp = sizeof($imp);
}else {
	$nimp=0;
}

if (isset($_POST['submit']) AND $_POST['submit'] == "addnimp") {
	$nimp++;
}

?>
<h2>Cadastro de exerc&iacute;cios</h2>
<?php 
if (isset($_POST['submit']) AND $_POST['submit'] == "submit") {
if (empty($id)) 
	echo $X->create($_POST['precondicoes'], $_POST['html'], $_POST['nome'], 
	array($_POST['peso'], $_POST['condicao'], $_POST['dica'], $_POST['imp']));
else
	echo $X->altera($_POST['precondicoes'], $_POST['html'], $_POST['nome'], 
	array($_POST['peso'], $_POST['condicao'], $_POST['dica'], $_POST['imp']));
} else {

echo "<form name=\"cadastro\" action=\"#\" method=\"post\" enctype=\"multipart/form-data\">";
echo "<p>Para a descri&ccedil;&atilde;o dos campos e funcionamento do corretor, leia a <a href='https://github.com/lageIBUSP/notaR/wiki/Cadastro'>ajuda</a>.";
echo "<br>Nome do exerc&iacute;cio:&nbsp;&nbsp;";
echo "<input type=\"text\" name=\"nome\"  style='width: 300px;' value=\"";
if (isset($_POST['nome'])) echo $_POST['nome'];
elseif (!empty($id)) echo $X->getNome();
echo "\">";
echo "<br>Precondi&ccedil;&otilde;es:&nbsp;";
echo "<br><textarea name=\"precondicoes\" rows=7 cols=80>";
if (isset($_POST['precondicoes'])) echo $_POST['precondicoes'];
elseif (!empty($id)) echo $X->getPrecondicoes();
echo "</textarea><br>HTML:<br><textarea name=\"html\" rows=7 cols=80>";
if (isset($_POST['html'])) echo $_POST['html'];
elseif (!empty($id)) echo $X->getHtml();
echo "</textarea>";


echo "<h3>Impedimentos</h3>";
echo "<table class='Cadastra'>";
for ($i = 0; $i < $nimp; $i ++) {
		echo "<tr><td><input type='text' class='long' name='imp[]' value='";
		if (isset($_POST['imp'][$i])) {echo htmlspecialchars($_POST['imp'][$i]);} 
		elseif (!empty($id) AND isset($imp[$i])) echo htmlspecialchars($imp[$i]->getPalavra());
		echo "'></td></tr>";
}
echo "</table>";
echo "<input type='hidden' id='nimp' name='nimp' value='$nimp'>";
echo "<button type=\"submit\" name=\"submit\" value=\"addnimp\">+</button>";

echo "<h3>Testes</h3>";
echo "<br>N&uacute;mero de testes:&nbsp;&nbsp;";
echo "<input type=\"text\" name=\"ntestes\" value=\"".$ntestes."\">&nbsp;";
echo "<button type=\"submit\" name=\"submit\" value=\"alterar\">alterar</button>";

echo "<table class='Cadastra'><tr><td><center><b>Ordem</b></center></td><td><center><b>Peso</b></center></td><td><center><b>Condi&ccedil;&atilde;o</b></center></td><td><center><b>Dica</b></center></td></tr>\n";
for ($i = 0; $i < $ntestes; $i ++) {
	if (!empty($id)) {$T = new Teste($id, $i+1);}
		echo "<tr>";
		echo "<td><center>".($i+1)."</center></td>";
		echo "<td><input type='text' name='peso[]' value='";
		if (isset($_POST['peso'][$i])) {echo $_POST['peso'][$i];} 
		elseif (!empty($id) AND $T->peso()) echo $T->peso();
		else {echo 1;}
		echo "'></td><td><input class='long' type='text' name='condicao[]' value=\"";
		if (isset($_POST['condicao'][$i])) {echo htmlspecialchars($_POST['condicao'][$i]);}
		elseif (!empty($id)) echo htmlspecialchars($T->condicao());
		echo "\"></td><td><input class='long' type='text' name='dica[]' value=\"";
		if (isset($_POST['dica'][$i])) {echo htmlspecialchars($_POST['dica'][$i]);}
		elseif (!empty($id)) echo htmlspecialchars($T->dica());
		echo "\"></td></tr>";
}
		echo "</table>";


echo "<button type=\"submit\" name=\"submit\" value=\"submit\">OK</button>";
echo "</form>";
}
?>
</div>
</body>
</html>
