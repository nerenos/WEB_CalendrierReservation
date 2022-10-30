function Initialisation(){
	var monDiv = document.getElementById("CHP_PLN");
	var listeDivEnfants = monDiv.getElementsByTagName("SPAN");
	for (var i=0;i<listeDivEnfants.length;i++){
		listeDivEnfants[i].addEventListener('click', SelJourDebut, false);
	}
	
}

function CalendrierReset(){
	var listbgc = "";
	var monDiv = document.getElementById("CHP_PLN");
	var listeDivEnfants = monDiv.getElementsByTagName("SPAN");
	for (var i=0;i<listeDivEnfants.length;i++){
		if (listeDivEnfants[i].id != "CEL_Vide" && listeDivEnfants[i].id.substring(0, 4) == "CEL_"){
			listbgc += listeDivEnfants[i].id + "/";
			if (listeDivEnfants[i].style.backgroundColor == "green"){
				listeDivEnfants[i].style.backgroundColor = listeDivEnfants[i].style.getPropertyValue("--AquaGradiant");
			}	
		}
	}
	alert(listbgc);
}

function SelJourDebut(e){
	if (e.target.id != "CEL_Vide" && e.target.id.substring(0, 4) == "CEL_"){
		CalendrierReset();
		e.target.style.backgroundColor = "green";
	}
}

window.onload = Initialisation;