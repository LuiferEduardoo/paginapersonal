function validarFormulario ()
{
    const nombre = document.getElementById('nombre').value; 
    const email = document.getElementById('email').value;
    const mensaje = document.getElementById('mensaje').value;
    if(nombre == "" || email == "" || mensaje == "")
    {
        alert("Los campos son obligatorios");
        return false;
    } 
    return true;
}