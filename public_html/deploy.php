<?php header('Content-Type: text/html;charset=UTF-8');?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
    </head>
    <body>
	
        <div class="container">
	    <div style="text-align:right">
		<a class="btn" href="/deploy.php<?php echo ($_GET['action']!== 'up' ? '?action=up':'')?>">
		    <?php echo ($_GET['action'] == 'up')? 'Статус' : 'Обновить' ?>
		</a>
	    </div>
	    <?php
                $action = 'st';
		if($_GET['action'] == 'up')
		{
		    $action = 'up';
		}
                $command = escapeshellcmd('svn ' . $action . ' '.dirname(dirname(__FILE__)) .
                //' --username ' . $_POST['user']['email'] .
                //' --password ' . $_POST['user']['password'] .
                ' --no-auth-cache --non-interactive --trust-server-cert');
                //echo $command;
                exec($command, $output, $returnV);
                echo "<pre>";
                echo $returnV == 0 ? "Command executed successfully.\r\n" : "Error executing command.\r\n";
                echo "\r\n";
                echo implode("\r\n", $output);
                echo "</pre>";
            ?>
        </div>
    </body>
</html>