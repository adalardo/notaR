<?php require('head.php');
if (! $user->admin()) {
	echo "Acesso negado";
	exit;
}
?>
<h2>Relat&oacute;rio de dificuldades</h2>
<p>Escolha a turma: <?php echo SelectTurma(); ?></p>
<p>M&eacute;dia de tentativas por aluno que entregou cada exerc&iacute;cio:</p>
<table>
<tr><td>Exerc&iacute;cio</td><td>Tentativas</td></tr>

<?php
$lista_exs = mysql_query("select id_exercicio, round(count(id_aluno)/count(distinct id_aluno)) from nota join aluno using(id_aluno) where id_turma=".$TURMA->getId()." group by id_exercicio order by 2 desc");

while ($E = mysql_fetch_array($lista_exs)) {
	$ex = new Exercicio(NULL, $E[0]);
	echo "<tr><td>".$ex->getNome()."</td><td>".$E[1]."</td></tr>";
}

?>
</table>
</div>
</body>
</html>
