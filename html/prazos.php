<?php require('head.php');
if (! $user->admin()) {
	echo "Acesso negado";
	exit;
}
if(isset($_POST['turma']))
	$turma = mysql_real_escape_string($_POST['turma']);
else {
		$T = mysql_fetch_array(mysql_query("SELECT MIN(id_turma) FROM turma"));
		$turma = $T[0];
}
if (isset($_POST['submit']) AND $_POST['submit'] == "atualiza") {
		$post = mres($_POST);
		foreach (array_keys($post) AS $key) {
				if (strpos($key, "ld_")) {
						$new = substr($key, 4);
						$ex = substr($key, 6);
						if ($post[$key] != $post[$new]) {
								if($post[$key] =='') { // novo
										mysql_query("INSERT INTO prazo (id_exercicio, id_turma, prazo) VALUES ($ex, $turma, '".$post[$new]."')");
								}
								elseif($post[$new] == '') { // removido
										mysql_query("DELETE FROM prazo WHERE id_exercicio=$ex AND id_turma=$turma");
								}
								else { // atualizar
										mysql_query("UPDATE prazo SET prazo='".$post[$new]."' WHERE id_exercicio=$ex AND id_turma=$turma");
								}
						}
				}
		}

}

?>
<h2>Administra&ccedil;&atilde;o de prazos</h2>
<p>Escolha a turma</p>
<form action='prazos.php' method='POST'>
	<select id='turma' name='turma'>
<?php
$lista_turmas = mysql_query("SELECT id_turma FROM turma ORDER BY id_turma ASC");

while ($T = mysql_fetch_array($lista_turmas)) {
	$loop_turma = new Turma($T[0]);
	echo "	<option value=".$loop_turma->getId();
	if($loop_turma->getId() == $turma) echo " selected";
	echo ">".$loop_turma->getNome()."</option>";
}
?>
	</select>
	<button type='submit' name='submit' value='turma'>ok</button>
<p>Prazos cadastrados:</p>
<table>
<tr><th>Exerc&iacute;cio</th><th>Data</th></tr>
<?php
$lista_exs = mysql_query("SELECT id_exercicio FROM exercicio ORDER by nome");

while ($E = mysql_fetch_array($lista_exs)) {
	echo "	<tr>";
	$ex = new Exercicio(NULL, $E[0]);
	echo "		<td>".$ex->getNome()."</td><td>";
	echo "<input type='text' name='ex".$ex->getId()."' value='".$ex->getPrazo($turma)."' style='width: 150px'>";
	echo "<input type='hidden' name='old_ex".$ex->getId()."' value='".$ex->getPrazo($turma)."'>";
	echo "</td></tr>";
}
?>
</table>
<p>Para cadastrar novos prazos ou alterar os j&aacute; cadastrados, digite a data e hora na caixa de texto correspondente, no formato "YYYY-MM-DD HH:MM:SS".</p>
<button type='submit' name='submit' value='atualiza'>Atualiza</button>
</form>
</div>
</body>
</html>
