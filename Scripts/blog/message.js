if (window.history.replaceState)
{
    // verificamos disponibilidad
    window.history.replaceState(null, null, window.location.href);
}
const cerrarIcon = document.getElementById('cerrar').addEventListener('click', function(){
    let message = document.getElementById('message');
    message.style.display = "none";
})
