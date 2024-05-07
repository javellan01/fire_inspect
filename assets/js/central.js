// Bagunça Central de funções JScript Javell_2018-2022

//Set JQuery DatePicker

	function loadPhp(str) {
		var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
		document.getElementById("main").innerHTML = this.responseText;
		
		$( function() {
		$( ".date" ).datepicker();

		$('#formCNPJ').mask('00.000.000/0000-00', {reverse: false});
		$('#formCPF').mask('000.000.000-00', {reverse: false});
		$('#formTel').mask('(00) #0000-0000', {reverse: false});
		$('#formData').mask('00/00/0000', {reverse: false});
		$('#formDataA').mask('00/00/0000', {reverse: false});
		$('#formDataB').mask('00/00/0000', {reverse: false});
		
		$('.modal').on('hide.bs.modal', function (){
			loadPhp(str);
		});
		
		});		

	xhttp.open("GET", str, true);
	xhttp.send();
	}
		
	