<?php 
	include_once 'Dynamic_dropbox.php';
	
?>
<!DOCTYPE html>
<html>
	<head>
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			 $(".url").click(function(){
				var c_url = $("#competitor").val();
				$("#Myform").submit();
			//$("#competitor").val(0);
				});
		});
	</script>
	<style>
		.wrapper {
			  margin-top:70px;
			  background: white;
			  margin-left:600px;
			  padding: 2em;
			  width: 80%;
			}
		
	</style>
	</head>
	<body>
		<form class="wrapper" action="Newcrawler.php" id="Myform" method="POST">
			<select id="competitor" name="competitor">
				<option value=0>Select</option>
				<?php fetchUrl(); ?>
			</select>
				<br/><br/>
				<input type="submit" name="submit" value="tsg Urls" class="url">
				<input type="submit" name="submit" value="All Urls" class="url">
		</form>
	</body>
</html>