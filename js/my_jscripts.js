function openSysnotes(){
	alert("Attention:\n1. <6/6/2012> RIC Dr Ngo/Intern Ecarma (MICU): Please correct birthdate of patient E. Hermosa MICU B7. TY.");
}

function loadYearA(){
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {
	    
	    document.getElementById("yearA").innerHTML = xmlhttp.responseText;
	    }
	  }
	 xmlhttp.open("GET", "/medisys/webpage/fback.txt",true);
	 xmlhttp.send();
	
}

function loadYearB(){
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {
	    
	    document.getElementById("yearB").innerHTML = xmlhttp.responseText;
	    }
	  }
	 xmlhttp.open("GET", "/medisys/webpage/yearB.txt",true);
	 xmlhttp.send();
}

function showInfo2(){
    alert("New in this version (April 2012): \n 1. AJAX-enhanced patient and resident search engines (ala-Google)\n2. Jquery enhanced CSS styling (ongoing changes)\n3. \"focus on one\" admissions interface\n4. Improved abstract, discharge summary and other forms compatible with Firefox and Chrome\n5. Auto-compute costs for Sagipbuhay Forms\n6. Feedback entry and viewing\n7. Bulletin boards\n\n\n\n\n\n\n hucomd042012");
}

function showFback(){
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {
	    
	    document.getElementById("viewfback").innerHTML = xmlhttp.responseText;
	    }
	  }
	 xmlhttp.open("GET", "/medisys/webpage/fback.txt",true);
	 xmlhttp.send();
}
function showQuiz(){
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {
	    
	    document.getElementById("quizcopy").innerHTML=xmlhttp.responseText;
	    }
	  }
	xmlhttp.open("GET", "/medisys/quiz/quiznotes.htm",true);
	xmlhttp.send();
}

function showSysNotes(x){
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {
	    
	    document.getElementById("sysnotes").innerHTML=xmlhttp.responseText;
	    }
	  }
	if (x==1){
	xmlhttp.open("GET", "sysnotes.txt",true);
	}
	if (x==2){
		xmlhttp.open("GET", "lu7notes.txt",true);
		}
	if (x==3){
		xmlhttp.open("GET", "lu6notes.txt",true);
		}
	if (x==4){
		xmlhttp.open("GET", "quiz/quiznotes.htm",true);
		}

	xmlhttp.send();
	
}

function prepList() {
      $("#census_table tr td").add("#census_table table tr td").css("font-weight", "bold");
      $("#census_table tr:nth-child(1) th:nth-child(1)").addClass("gendata_col"); 
      $("#census_table tr:nth-child(1) th:nth-child(2)").addClass("plist");
      $("#census_table tr:nth-child(1) th:nth-child(3)").add("#census_table tr:nth-child(1) th:nth-child(4)").addClass("refs");
      $("#census_table tr:nth-child(1) th:nth-child(5)").addClass("buttons_col");
      $("#micu_body").hide();
      $("#er_body").hide();
      $("#selected_body").hide();
      $(".clickable").hover(function() {
                    $(this).addClass('hover');
                    }, function() {
                    $(this).removeClass('hover');
                    }
                    );
      $("#editable tr").hover(function() {
                    $(this).addClass('hover').addClass('highlight');
                    }, function() {
                    $(this).removeClass('hover').removeClass('highlight');
                    }
                    );
              
        }
		
function prepresList() {
      $("#census_table tr td").add("#census_table table tr td").css("font-weight", "bold");
      $("#census_table tr:nth-child(1) th:nth-child(1)").addClass("gendata_col"); 
      $("#census_table tr:nth-child(1) th:nth-child(2)").addClass("plist");
      $("#census_table tr:nth-child(1) th:nth-child(3)").add("#census_table tr:nth-child(1) th:nth-child(4)").addClass("refs");
      $("#census_table tr:nth-child(1) th:nth-child(5)").addClass("buttons_col");
      $("#micu_body").hide();
      $("#er_body").hide();
      $("#selected_body").hide();
	  $("#comx").hide();
	  $("#preop").hide();
      $(".clickable").hover(function() {
                    $(this).addClass('hover');
                    }, function() {
                    $(this).removeClass('hover');
                    }
                    );
      $("#editable tr").hover(function() {
                    $(this).addClass('hover').addClass('highlight');
                    }, function() {
                    $(this).removeClass('hover').removeClass('highlight');
                    }
                    );
              
        }		
		
function hideMe(a){

 a.hide();

}
/*
 *resident census part
 *
*/
function showresWards(){
    $("#ward_body").show();
    $("#micu_body").hide();
    $("#er_body").hide();
	$("#preop").hide();
	$("#comx").hide();
    $("#selected_body").hide();	
}


function showresMicu(){
    $("#micu_body").show();
    $("#ward_body").hide();
    $("#er_body").hide();
	$("#preop").hide();
	$("#comx").hide();
    $("#selected_body").hide();	
}

function showresER(){
    $("#er_body").show();
    $("#ward_body").hide();
    $("#micu_body").hide();
	$("#preop").hide();
	$("#comx").hide();
    $("#selected_body").hide();	
}

function showresPreop(){
    $("#ward_body").hide();
    $("#micu_body").hide();
    $("#er_body").hide();
	$("#preop").show();
	$("#comx").hide();
    $("#selected_body").hide();	
}

function showresComx(){
    $("#comx").show();
    $("#ward_body").hide();
    $("#micu_body").hide();
    $("#er_body").hide();
	$("#preop").hide();
    $("#selected_body").hide();	
}

function showresEdit(){
    $("#ward_body").hide();
    $("#micu_body").hide();
    $("#er_body").hide();
	$("#preop").hide();
	$("#comx").hide();
    $("#selected_body").show();	
}

function showMicu(){
    $("#ward_body").hide();
    $("#micu_body").show();
    $("#er_body").hide();
    $("#selected_body").hide();    
}

function showWards(){
    $("#ward_body").show();
    $("#micu_body").hide();
    $("#er_body").hide();
    $("#selected_body").hide();    
}

function showEr(){
    $("#er_body").show();
    $("#micu_body").hide();
    $("#ward_body").hide();
    $("#selected_body").hide();
}

function showSelected(){
    $("#selected_body").show();
    $("#micu_body").hide();
    $("#ward_body").hide();
    $("#er_body").hide();
}

function searchResidents(my_service, my_dispo, one_gm, stp1){
var clue;
clue = $("#search_r").val();
if (clue.length < 3)
  {
  document.getElementById("residentTable").innerHTML="";
  return;
  } 
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()

  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    
    document.getElementById("residentTable").innerHTML=xmlhttp.responseText;
    }
  }
var rand = parseInt(Math.random()*999999999999);  
xmlhttp.open("GET","menu/get_like_resident?clue="+clue+"&my_service="+my_service+"&my_dispo="+my_dispo+"&one_gm="+one_gm+"&stp1="+stp1+"&rand="+rand ,true);
xmlhttp.send();
}    





function searchPatients(my_service, my_dispo, one_gm, stp1){
var clue;
clue = $("#search_p").val();
if (clue.length < 3)
  {
  document.getElementById("patientTable").innerHTML="";
  return;
  } 
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    
    document.getElementById("patientTable").innerHTML=xmlhttp.responseText;
    }
  }
var rand = parseInt(Math.random()*999999999999);  
xmlhttp.open("GET","menu/get_like_patient?clue="+clue+"&my_service="+my_service+"&my_dispo="+my_dispo+"&one_gm="+one_gm+"&stp1="+stp1+"&rand="+rand ,true);
xmlhttp.send();
}    

function getAdm(adm, my_service, my_dispo, one_gm, stp1)
{
showSelected();
document.getElementById("selected_body").innerHTML="";
if (adm=="")
  {
  document.getElementById("selected_body").innerHTML="";
  return;
  } 
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById("selected_body").innerHTML=xmlhttp.responseText;
    }
  }
var rand = parseInt(Math.random()*999999999999);  
xmlhttp.open("GET","get_selected_admission?aid="+adm+"&my_service="+my_service+"&my_dispo="+my_dispo+"&one_gm="+one_gm+"&stp1="+stp1+"&rand="+rand ,true);
xmlhttp.send();
}    

function getresAdm(adm, my_service, my_dispo, one_gm, stp1)
{
showSelected();
document.getElementById("selected_body").innerHTML="";
if (adm=="")
  {
  document.getElementById("selected_body").innerHTML="";
  return;
  } 
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById("selected_body").innerHTML=xmlhttp.responseText;
    }
  }
var rand = parseInt(Math.random()*999999999999);  
xmlhttp.open("GET","get_res_selected_admission?aid="+adm+"&my_service="+my_service+"&my_dispo="+my_dispo+"&one_gm="+one_gm+"&stp1="+stp1+"&rand="+rand ,true);
xmlhttp.send();
}    

