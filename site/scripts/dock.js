$(document).ready(
		function()
		{
			$('#dock').Fisheye(
				{
					maxWidth: 40,
					items: 'a',
					itemsText: 'span',
					container: '.dock-container',
					itemWidth: 40,
					proximity: 40,
					halign : 'center'
				}
			)
			
		}
	);
