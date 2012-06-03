var dtCh= "-";
var minYear=0000;
var maxYear=2100;

function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag){
	var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){   
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
   } 
   return this
}

function isDate(dtStr){
        var dtCh= "-";
	var daysInMonth = DaysArray(12)
	var pos1=dtStr.indexOf(dtCh)
	var pos2=dtStr.indexOf(dtCh,pos1+1)
	var strYear=dtStr.substring(0,pos1)
	var strMonth=dtStr.substring(pos1+1,pos2)
	var strDay=dtStr.substring(pos2+1)
	strYr=strYear
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
	}
	month=parseInt(strMonth)
	day=parseInt(strDay)
	year=parseInt(strYr)
	if (pos1==-1 || pos2==-1){
		alert("The date format should be : yyyy-mm-dd")
		return false
	}
	if (strMonth.length<1  || month>12){
		alert("Please enter a valid month")
		return false
	}
	if (strDay.length<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
		alert("Please enter a valid day")
		return false
	}
	if (strYear.length != 4 || year<minYear || year>maxYear){
		alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear)
		return false
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
		alert("Please enter a valid date")
		return false
	}
return true
}

function validateDispoDateformat(form){
	var dt=form.date_out;
	if (isDate(dt.value)==false){
		dt.focus()
		return false
	}
    return validateDispoDate(form);
 }
function validateDispoDateformat2(form){
	var dt=form.date_out;
	if (isDate(dt.value)==false){
		dt.focus()
		return false
	}
    return validateDispoDate2(form);
 }

function validateAdmDateformat(form){
	var dt=form.date_in;
	if (isDate(dt.value)==false){
		dt.focus()
		return false
	}
    return validateAdmit(form);
 }

function validatePbdayDateformat(form){
	var dt=form.p_bday;
	if (isDate(dt.value)==false){
		dt.focus()
		return false
	}
        
 }

function validateAdmstatus(form)
    {
	 var a=form.adm_status.value;
	 if (a=="Admitted"){
	     alert("Patient is currently still Admitted!");
		 return false;
	  }
	 else
	  return true;	 
	
	}

function validateName()
	{
	 var a=document.forms["find_resident"]["rname"].value;
	 
	 
	 if (a==null || a=="")	  {
	    alert("Please ENTER text to search.");
	    return false;
	  }
	 
	  return true;
	} 
	
function validatePname()
	{
	 var a=document.forms["find_pname"]["pname"].value;
	 
	 
	 if (a==null || a=="") {
	    alert("Please ENTER text to search in Patient Name.");
	    return false;
	  }
	 
	  return true;
	}	
	
function validatePcnum()
	{
	var b=document.forms["find_pcnum"]["cnum"].value;
	if (b==null || b=="") {
	    alert("Please ENTER text to search in Case Number.");
	    return false;
	  }
	 
	  return true;
	}	
	
function validateRedit(form)
    {
     var a=form.rname.value;
	 var b=form.dstart.value;
	 var c=form.status.value;
	 if ((a==null || a=="") || (b==null || b==""))  {
	    alert("Please FILL UP Name and Date Started.");
	    return false;
	  }
	  var c=confirm("The Following will be changed:\n \n Name: " + a + "\n Date Started: " + b + "\n Active: " + c + "\n Confirm Changes?");
	  return c;  	 	    
     }	

function validatePedit(form)
    {
	     var a=form.p_name.value;
		 var b=form.cnum.value;
		 var c=form.p_sex.value;
		 var d=form.p_bday.value;
		 
	     if ((a==null || a=="")||((b==null || b=="")||(d==null || d=="0000-00-00"))){
		 	 alert("Please FILL UP ALL patient information.");
	         return false;
	     }
             return validatePbdayDateformat(form);
		   
	}



	
function validateAedit(form)
    {
	     var a=form.date_in.value;
		 
	     if (a==null || a==""){
		 	 alert("Please FILL UP Admission Date.");
	         return false;
	     }
		 return validateAdmDateformat(form);  
	}	

function validateAdmit(form)
    {
	 var b=form.dispo.value;
	 var e=form.date_out.value;
	 
	 if ((b=='Admitted') && (e!="0000-00-00"))
	 {
	    alert ("DATE-OUT should be left 'unset' if patient is STILL Admitted");
	    return false;
	  } 
	 else 
	  {
	       return validateDispo(form);
	  }
	  
	}

function validateDispo(form)
	{
	
	 var b=form.dispo.value; 
	 var e=form.date_out.value;
	 
	 if ((b!='Admitted') && ((e=="")&&((e==null) &&(e=="0000-00-00")))) 
	 {
	    alert ("DATE-OUT should NOT BE EMPTY or '0000-00-00' if STATUS is NOT Admitted");
	    return false;
	  } 
	 else
	  {
	     return validateDispoDateformat(form);
             
	  } 
	  
	}	

function validateDispoDate(form)
	{
	 var b=form.date_in.value;
	 var e=form.date_out.value;
	 var f=form.dispo.value;

	

	 if ((b>e) && (f!='Admitted')) 
	 {
	    alert ("Disposition Date should be LATER than Admission date");
	    return false;
	  } 
	 else
	  {
	    return validateDischarge(form);
	  } 
	  
	 
	}	

function validateDispoDate2(form)
	{
	 var b=form.date_in.value;
	 var e=form.date_out.value;
	 var f=form.dispo.value;

	

	 if (b>e) 
	 {
	    alert ("Disposition Date should be LATER than Admission date");
	    return false;
	  } 
	 else
	  {
	    return true;
	  } 
	  
	 
	}	
function validateDischarge(form)
    {
	 var a=form.dispo.value;
	 if (a!="Admitted")
	 {
	   e=confirm("You are discharging this patient? \n Are you Sure?");
	   if (e==true)
	       return true;
	   else
	       return false;	   
	 }
	 else
	 {
	   return true;
	  } 
	
	}	
function validateTerm(form)
	{
	 var a=form.term.value;
	 
	 if (a=="" || a==null) 
	 {
	    alert ("Please Enter Search Term.");
	    return false;
	  } 
	 else
	  {
	    return true;
	  } 
	  
	 
	}	
function validateDatecensus(form)
	{
	 var a=form.date.value;
	 
	 if (a=="" || a==null) 
	 {
	    alert ("Please Enter Date to Search.");
	    return false;
	  } 
	 else
	  {
	    return true;
	  } 
	  
	 
	}	
function validatePeriod(form)
	{
	 var a=form.date1.value;
	 var b=form.date2.value;
	 if ((a=="" || a==null) || (b=="" || b==null)) 
	 {
	    alert ("Please Enter Start AND End Dates to Search.");
	    return false;
	  } 
	 else
	  {
	    return validatePerioddates(form);
	  } 
	  
	 
	}	
function validatePerioddates(form)
	{
	 var a=form.date1.value;
	 var b=form.date2.value;
	 if (a > b) 
	 {
	    alert ("Start Date should be EARLIER THAN End Date.");
	    return false;
	  } 
	 else
	  {
	    return true;
	  } 
	  
	 
	}	
function validateDelete(form)
	{
	 
	 e=confirm("Sigurado ka ba? \n\nBuburahin ang admisyon na ito!");
	 if (e==true)
	 {
	   return validateDeletetwo(form);
	  }
	 return e;
	  
	}
	
function validateDeletetwo(form)
	{
	 e=confirm("Sigurado ka na ba talaga? \n\nBuburahin ang admisyon na ito!");
	 if (e==true)
	 {
	   return validateDeletethree(form);
	  }
	 return e;
	 
	}
function validateDeletethree(form)
	{
	 e=confirm("Siguradong sigurado ka na ba talaga? \n\nWala nang balikan ito?");
	 return e;
	 
	}	
function helpAdmission()
    {
	 alert("Instructions: Edit Admission Data in this page.\nClick on Edit button to process editing of admission.\nReminders:\nA.Date Format is YYYY-MM-DD\nB.Date-OUT refers to date of discharge, transfer, mortality or HAMA\nC.You cannot edit patient-specific information here\nD.Edit Patient information in the Manage Patients Page");
	}

function showNotes(form)
    {
	 var a=form.pnotes.value;
	 
	 alert("Notes:\n" + a);
	
	}
function showPcp(form){
    var b=form.pcpdx.value;

    alert(b);
    
}
	
	
