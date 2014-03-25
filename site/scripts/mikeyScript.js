function showTasks(str){
		if (str==""){
		  document.getElementById("input").innerHTML="";
		  return;
		  } 
		if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else{// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function(){
		  if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("input").innerHTML=xmlhttp.responseText;
			}
		  }
		xmlhttp.open("GET","task.php?project="+str,true);
		xmlhttp.send();
		}
		
function parentAndPredeccessor(str){
		if (str==""){
		  document.getElementById("parentAndPredeccessor").innerHTML="";
		  return;
		  } 
		if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else{// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function(){
		  if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("parentAndPredeccessor").innerHTML=xmlhttp.responseText;
			}
		  }
		xmlhttp.open("GET","project.php?project="+str,true);
		xmlhttp.send();
}

//Brian hijacking this section
$(document).ready(function () {
	$(".submenu > a").bind("click", function(ev) {
		ev.preventDefault();
	});
});