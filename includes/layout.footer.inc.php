		</div>
		<?php
		if(isset($datatables_required) && $datatables_required == 1) {
			$datatable_script = <<<FILEDOC
			
		<script>
		$(document).ready( function () {
			$('#{$datatables_table_id}').DataTable( {
				{$datatables_option}
			} );
		} );
		</script>
		
FILEDOC;
			echo $datatable_script;
		};
		?>
		
    </body>
	
</html>