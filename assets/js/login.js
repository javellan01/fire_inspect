    $(function(){	

        $(".login-button").submit(function(event){

        var usuario = $("input#usuario").val();
        var senha = $("input#senha").val();
        
        return false;
        });

        var inusuario = document.getElementById("usuario");
        var insenha = document.getElementById("senha");
        var btnenter = document.getElementById("subm");
        // Get the modal
        var modal = document.getElementById("qrModal");

        // Get the button that opens the modal
        var btn = document.getElementById("openModal");
        var csbtn = document.getElementById("closeModal");

        // When the user clicks on the button, open the modal
        btn.onclick = function() {
        modal.style.display = "block";
            insenha.disabled = true;
            inusuario.disabled = true;
            inbtnenter.disabled = true;
        }

        csbtn.onclick = function() {
        modal.style.display = "none";
            insenha.disabled = false;
            inusuario.disabled = false;
            inbtnenter.disabled = false;
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            insenha.disabled = false;
            inusuario.disabled = false;
            inbtnenter.disabled = false;
        }
        }

        
    });	

    $(document).ready(function(){
		    
        $('#usuario').mask('000.000.000-00');

        });
        