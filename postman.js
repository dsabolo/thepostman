$(document).ready(
	function(){

		$('#postman_form').submit(
			function(){

				if($('#postman_form').valid()==true){
					$('#msg').html("");
					$.post('index.php',$("#postman_form").serialize(), function(data) {
						$('#msg').html(data);
					});
					
					return false;
				}
				else {
					var error = 1;
				}	
			}
			);
		
	}
	);
