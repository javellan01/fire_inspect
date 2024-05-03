// Bagunça Central de funções JScript Javell_2018-2022

//Set JQuery DatePicker

	function loadPhp(str) {
		var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
		document.getElementById("main").innerHTML = this.responseText;
		
		$( function() {
		$( ".date" ).datepicker();
		  
		$('.color-picker').spectrum({
			type: "text",
			hideAfterPaletteSelect: true,
			showAlpha: false,
			showButtons: false,
			allowEmpty: false
		  }); 
		} );

		$("#novaCategoria").click(function(e) {
			e.preventDefault();
			
				$.ajax({
				type: "POST",
				url: "catprocess.php",
				data: { 
					removeCategoria: '0',
					updateCategoria: '0',
					novaCategoria: $(this).val(),
					id_categoria: $('input#formCatId').val(),
					tx_color: $('input#formCatColor').val(),
					tx_nome: $('input#formCatName').val(),
						
				},
				
				success: function(result) {
					window.alert(result);
					
					
				},
				error: function(result) {
					window.alert(result);
					
				}
			});
		});

		$(".updateCategoria").click(function(e) {
			e.preventDefault();
			
			var id_categoria = $(this).attr("data-id_categoria");

			$.ajax({
				type: "POST",
				url: "catprocess.php",
				data: { 
					removeCategoria: '0',
					updateCategoria: $(this).val(),
					novaCategoria: '0',
					id_categoria: id_categoria,
					tx_color: $('input#color'+id_categoria).val(),
					tx_nome: $('input#nome'+id_categoria).val(),
						
				},
				
				success: function(result) {
					window.alert(result);
					loadPhp('configurar.php');
					
				},
				error: function(result) {
					window.alert(result);
					loadPhp('configurar.php');
				}
			});
		});

		$(".removeCategoria").click(function(e) {
			e.preventDefault();
			
			var id_categoria = $(this).attr("data-id_categoria");

			$.ajax({
				type: "POST",
				url: "catprocess.php",
				data: { 
					removeCategoria: $(this).val(),
					updateCategoria: '0',
					novaCategoria: '0',
					id_categoria: id_categoria,

						
				},
				
				success: function(result) {
					window.alert(result);
					loadPhp('configurar.php');
					
				},
				error: function(result) {
					window.alert(result);
					loadPhp('configurar.php');
				}
			});
		});	

		$('#modalPedido').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var cliente = button.data('cliente');
			var id_cliente = button.data('id_cliente');
			var modal = $(this);
			modal.find('#formSCliente.form-control').val(cliente);
			modal.find('#formidCliente.form-control').val(id_cliente);
		});
		
		$('#modalEdUsr').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var id_usuario = button.data('uid');
			var catuser = button.data('catuser');
			var uid = document.getElementById('uid'+id_usuario);
			
			var nome = uid.getElementsByClassName('uname');
			var tel = uid.getElementsByClassName('utel');
			var mail = uid.getElementsByClassName('umail');
			var cpf = uid.getElementsByClassName('ucpf');

			var modal = $(this);
			
			modal.find('#formUser.form-control').val(nome[0].innerHTML);
			modal.find('#formUserid.form-control').val(id_usuario);
			modal.find('#formCPF.form-control').val(cpf[0].innerHTML);
			modal.find('#formEmail.form-control').val(mail[0].innerHTML);
			modal.find('#formTel.form-control').val(tel[0].innerHTML);
			document.getElementById('formECatuser').options.selectIndex = catuser;
			
		});
		
		$('#formCNPJ').mask('00.000.000/0000-00', {reverse: false});
		$('#formCPF').mask('000.000.000-00', {reverse: false});
		$('#formTel').mask('(00) #0000-0000', {reverse: false});
		$('#formData').mask('00/00/0000', {reverse: false});
		$('#formDataA').mask('00/00/0000', {reverse: false});
		$('#formDataB').mask('00/00/0000', {reverse: false});
		
		$('.modal').on('hide.bs.modal', function (){
			loadPhp(str);
		});
		
		}		
			
		
		};
	
	xhttp.open("GET", str, true);
	xhttp.send();
		}
		
	