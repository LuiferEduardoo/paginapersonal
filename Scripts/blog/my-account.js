if (window.history.replaceState)
{   // verificamos disponibilidad
    window.history.replaceState(null, null, window.location.href);
 }
let formularioPhoto = document.querySelector('.form-photo');
let formularioUsername = document.querySelector('.form-username');
let formularioName = document.querySelector('.form-nombre');
let formularioEmail = document.querySelector('.form-email');
let formularioPassword = document.querySelector('.form-contraseña');

const abrirFormulario = (formulario) => {
    formulario.style.display = "flex";
    document.body.classList.add('darkened');
}
document.getElementById('photo').addEventListener('click', () => {
    abrirFormulario(formularioPhoto);
    formularioPhoto.addEventListener('submit', function(event)
    {
        const input = document.getElementById('input_file').value;
        validarFormularioOne(event, input, this);
    }); 

});  
document.getElementById('username').addEventListener('click', () => {
    abrirFormulario(formularioUsername);
    formularioUsername.addEventListener('submit', function(event)
    {
        const input = document.getElementById('input_username').value;
        validarFormularioUserName(event, input, this);
    }); 

});

document.getElementById('nombre').addEventListener('click', () => {
    abrirFormulario(formularioName);
    formularioName.addEventListener('submit', function(event)
    {
        const input = document.getElementById('input_name').value;
        validarFormularioOne(event, input, this);
    }); 
});

document.getElementById('email').addEventListener('click', () => {
    abrirFormulario(formularioEmail);
    formularioEmail.addEventListener('submit', function(event)
    {
        const input1 = document.getElementById('input_email').value;
        const input2 = document.getElementById('input_password').value;
        validarFormularioTwo(event, input1, input2, this);
    }); 
});

document.getElementById('contraseña').addEventListener('click', () => {
    abrirFormulario(formularioPassword);
    formularioPassword.addEventListener('submit', function(event)
    {
        const input1 = document.getElementById('input_currente_password').value;
        const input2 = document.getElementById('input_new_password').value;
        const input3 = document.getElementById('input_password_replay').value;
        validarFormularioThree(event, input1, input2, input3, this);
    }); 
});

const cerrarIcons = document.querySelectorAll('.cerrarIcon');
cerrarIcons.forEach(cerrarIcon => {
    cerrarIcon.addEventListener('click', function(){
        const formularios = document.querySelectorAll('form');
        formularios.forEach(formulario => {
            formulario.style.display = "none";
            document.body.classList.remove('darkened');
        })
    });
});
  
  function validarFormularioOne(evento, input, form) {
     // Esta función se ejecutará cuando se envíe el formulario

    evento.preventDefault();
     // Previene que el formulario se envíe de forma predeterminada

    if(input.length == 0){
    // Si el campo de usuario está vacío
      alert('No puedes dejar este campo vacio');
      // Muestra una alerta

      return;
      // Retorna, para que el demas codigo no se ejecute
    }
    form.submit();
    // Si ambos campos no están vacíos, envía el formulario
  }


  function validarFormularioUserName(evento, input, form) {
    // Esta función se ejecutará cuando se envíe el formulario

   evento.preventDefault();
    // Previene que el formulario se envíe de forma predeterminada

    let regex = /[^a-zA-Z0-9_-]/; 
    if ((input.length == 0))
    {
        // Si el campo de usuario está vacío
        alert('No puedes dejar este campo vacio');

        return;
        // Retorna, para que el demas codigo no se ejecute
    }
   if(regex.test(input)){
   // Si el campo de usuario está vacío
     alert('El campo contiene elementos que no son validos');
     // Muestra una alerta

     return;
     // Retorna, para que el demas codigo no se ejecute
   }
   form.submit();
   // Si ambos campos no están vacíos, envía el formulario
 }


 function validarFormularioTwo(evento, input1, input2, form) {
    // Esta función se ejecutará cuando se envíe el formulario

   evento.preventDefault();
    // Previene que el formulario se envíe de forma predeterminada
    let emailRegex = /^((?!.*@.*\..*).)*$/;
    if ((input1.length == 0 || input2.length ==0))
    {
        // Si el campo de usuario está vacío
        alert('No puedes dejar este campo vacio');

        return;
        // Retorna, para que el demas codigo no se ejecute
    }
    if (emailRegex.test(input1))
    {
        // Si el campo de usuario está vacío
        alert('Escriba un correo electronico');

        return;
        // Retorna, para que el demas codigo no se ejecute
    }
   form.submit();
   // Si ambos campos no están vacíos, envía el formulario
 }
 function validarFormularioThree(evento, input1, input2, input3, form)
 {
    // Esta función se ejecutará cuando se envíe el formulario
    let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

   evento.preventDefault();
   // Previene que el formulario se envíe de forma predeterminada

   if (input1.length == 0 || input2.length == 0 || input3.length == 0)
   {
       // Si el campo de usuario está vacío
       alert('No puedes dejar este campo vacio');

       return;
       // Retorna, para que el demas codigo no se ejecute
   }
   if (input2 != input3) {
        alert('Las contraseñas no son iguales');
        return;
       // Retorna, para que el demas codigo no se ejecute
   }
   if (!passwordRegex.test(input3))
   {
    const imprimirError = document.getElementById("message_error");
    imprimirError.innerText = "La contraseña no cumple con las espacificaciones";
    imprimirError.style.color = "red";
    return;
       // Retorna, para que el demas codigo no se ejecute
   }
  form.submit();
  // Si ambos campos no están vacíos, envía el formulario
 }