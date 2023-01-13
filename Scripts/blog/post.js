const profileLogo = document.querySelector('.profile_logo').addEventListener('click', function(){
    let profileMenu = document.querySelector('.profile_menu');
    profileMenu.classList.toggle('inactive');
});

document.addEventListener("DOMContentLoaded", function() {
    // Esta función se ejecutará cuando el documento HTML haya sido completamente cargado y parseado
    
    document.getElementById("form").addEventListener('submit', validarFormulario); 
  });
  
  function validarFormulario(evento) {
     // Esta función se ejecutará cuando se envíe el formulario

    evento.preventDefault();
     // Previene que el formulario se envíe de forma predeterminada

    const title = document.getElementById('input_titulo').value;
     // Obtiene el valor del campo de texto con ID "input_titulo"

     const contents = document.getElementById('textare_contenido').value;
     // Obtiene el valor del campo de texto con ID "textare_contenido"
    if(title.length == 0 || contents.length ==0)
    {
    // Si los dos campos están vacios
      alert('No puedes dejar los campos vacios');
      // Muestra una alerta

      return;
      // Retorna, para que el demas codigo no se ejecute
    }
    const namePage = document.getElementById('input_nombre_pagina').value;
    // Obtiene el valor del campo de texo con ID "input_nombre_pagina"

    // Esta expresión regular bucsa cualquier caracter que no sea una letra, numero o el signo - 
    let regex = /[^a-z0-9-]/;

    if (regex.test(namePage)) {
        // Si el campo tiene otros elementos a los mencionado anteriormente

        alert('El campo contiene elementos que no son validos');
        // Muestra una alerta 
      return;
      // Retorna, para que el demas codigo no se ejecute
    }
    const file = document.getElementById('input_file').value;
    // Obtiene el valor del campo de texo con ID "input_nombre_pagina"

    if (file.length == 0) {
         // Muestra una alerta
        alert("Tiene que seleccionar una imagen");

        return;
        // Retorna, para que el demas codigo no se ejecute
    }

    this.submit();
    // Si todos los campos estan bien, se envía el formulario
  }