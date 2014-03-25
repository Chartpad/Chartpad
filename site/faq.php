<script>
  $(function() {
    $( "#accordion" ).accordion({
      collapsible: true
    });
  });
  </script>
  <script>
  $(document).ready(function() {

  /* This is basic - uses default settings */
  
  $("a.images").fancybox();
  
  });</script>
<?php
    include("includes/dbconnect.php");
    echo ("<h1 class='title'>Frequently Asked Questions</h1>
	<section class='help'>
	<article>
	<div id='accordion'>");

    $sql= mysql_query("SELECT * FROM INSE_tblFAQ")
    or die(mysql_error());
    $result= mysql_num_rows($sql);
    
    while($INSE_faq = mysql_fetch_array( $sql ))    
    {
        echo('
		
        <h3>Q: ' . $INSE_faq['faqQ'] . '</h3>
        <div>' . $INSE_faq['faqA'] . '</div>
        ');
    }
	echo ("
	</div>
	</article>
	</section>");
?>