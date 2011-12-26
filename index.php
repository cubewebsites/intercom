<?php
	$base	=	str_replace('index.php','',$_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html onclick="$('#input').focus()">
  <head>
    <title>Intercom Development</title>
    <link rel='stylesheet' href='style.css' type='text/css' />	
	<link rel='stylesheet' href='js/prettyPhoto/css/prettyPhoto.css' type='text/css' />
	
	<script type="text/javascript">
		var basepath	=	'<?php echo $base ?>'
	</script>
	
    <!-- jQuery -->
    <script type='text/javascript' 
      src='https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js'>
    </script>
    
    <!-- Intercom -->
    <script type='text/javascript' src='intercom.js'></script>
    
    <!-- Plugins -->    
	<script type='text/javascript' src='youtube.js'></script>
    
  </head>
  <body>
    <!-- Code to run when page is ready -->
    <script>
    $("document").ready(function() {
      output("Type 'help' for installed plugins. Type 'youtube run' to start the app");
    });
    </script>
  
    <div>
    <div id='output' style='width:100%'></div>
    &raquo;&nbsp;<input id="input" onkeydown="checkKey(event)" 
        type="text" style="width:90%; border: none;" />
    </div>
	
	<script type='text/javascript' src='js/prettyPhoto/js/jquery.prettyPhoto.js'></script>
	
  </body>
</html>
