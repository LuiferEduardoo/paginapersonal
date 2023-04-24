document.addEventListener("DOMContentLoaded", function() {
    // Esta función se ejecutará cuando el documento HTML haya sido completamente cargado y parseado
    
    document.getElementById("formulario").addEventListener('submit', validarFormulario); 
  });
  
  function validarFormulario(evento) {
     // Esta función se ejecutará cuando se envíe el formulario

    evento.preventDefault();
     // Previene que el formulario se envíe de forma predeterminada

    const usuario = document.getElementById('input_usuario').value;
     // Obtiene el valor del campo de texto con ID "input_usuario"

    if(usuario.length == 0) {
    // Si el campo de usuario está vacío
      alert('No puedes dejar el campo de usuario vacio');
      // Muestra una alerta

      return;
      // Retorna, para que el demas codigo no se ejecute
    }
    const clave = document.getElementById('input_password').value;
    // Obtiene el valor del campo de texo con ID "input_password"

    if (clave.length == 0) {
        // Si el campo de clave esta vacio

        alert('No puedes dejar el campo de contraseña vacio');
        // Muestra una alerta 
      return;
      // Retorna, para que el demas codigo no se ejecute
    }
    this.submit();
    // Si ambos campos no están vacíos, envía el formulario
  }