/* Esta declaración try...catch intenta seleccionar un elemento con la clase '.message_img'
 y agregarle un controlador de eventos 'click' que ejecutará la función 'closeMessage()' */
try {
    const messageImg = document.querySelector('.message_img').addEventListener('click', closeMessage);
}

/* Si ocurre un error al intentar seleccionar el elemento (por ejemplo, si no existe ningún elemento con esa clase)
 el error se manejará en este bloque catch */
catch(error) {
     /* En lugar de seleccionar el elemento con la clase '.message_img', se selecciona el elemento con la clase '.message_error_img'
      y se le agrega un controlador de eventos 'click' que ejecutará la función 'closeMessageError()'*/
        const messageErrorImg = document.querySelector('.message_error_img').addEventListener('click', closeMessageError);
}

const message = document.querySelector('.message');

// Esta función selecciona el elemento con la clase '.message' y le agrega la clase 'close'
function closeMessage()
{
    message.classList.add('close');
}

// // Esta función selecciona el elemento con la clase '.message_error' y le agrega la clase 'close'
function closeMessageError(){
    const message_error = document.querySelector('.message_error');
    message_error.classList.add('close');
}

// Esta función valida el formulario obteniendo el valor de los campos de entrada con los IDs 'nombre', 'email' y 'mensaje'
function validarFormulario ()
{
    const nombre = document.getElementById('nombre').value; 
    const email = document.getElementById('email').value;
    const mensaje = document.getElementById('mensaje').value;

    // Si alguno de estos campos está vacío, se muestra una alerta y se devuelve 'false'
    if(nombre == "" || email == "" || mensaje == "")
    {
        alert("Los campos son obligatorios");
        return false;
    } 

     // Si todos los campos tienen algún valor, se devuelve 'true'
    return true;
}