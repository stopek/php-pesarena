			
			<script type="text/javascript">
			$(document).ready(function() {
			  $('#file_upload').uploadify({
				'uploader'  : 'http://www.pesarena.pl/js/uploadify/uploadify.swf',
				'script'    : 'http://www.pesarena.pl/js/uploadify/uploadify.php',
				'cancelImg' : 'http://www.pesarena.pl/js/uploadify/cancel.png',
				'folder'    : '/grafiki/avatar-clans/{$id}',
				'auto'      : true,
				'multi'     : false,
				'removeCompleted': false,
				'fileExt'     : '*.jpg',
				'fileDesc'    : 'Image Files',
				'displayData' : 'speed',
				'buttonText': "WYBIERZ LOGO",
				'sizeLimit'   : 10120000
			  });
			});
			</script>
			
			
		<div id="uploadFiles">
			<input id="file_upload" name="file_upload" type="file" />
		</div>