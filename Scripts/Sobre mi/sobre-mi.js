//se trae la id de HTML
const text = document.getElementById("edad_p")

//se le asigna a la variable el valor del mes de nacimiento
const month_birthday = 9;

//se le asigna a la variable el valor del año de nacimiento
const year_i_was_born = 2005;

//se crea un nuevo objeto tipo date
let today = new Date();

// con el `getMonth()` se devuelve el mes en el que se esta
let month = today.getMonth() + 1;

// con el `getFullYear()` se devuelve el año en el que se esta
let year = today.getFullYear();

function CalculAge(month_birthday, year_i_was_born, month, year) {
    if (month >= month_birthday) 
{
    //Se calcula la edad dependiendo del año actual y el año de nacimiento
    let years_old = year - year_i_was_born;
    return years_old;
}
else
{
    //Se calcula la edad dependiendo del año actual y el año de nacimiento, pero se le resta 1
    let years_old_else = (year - year_i_was_born) -1;
    return years_old_else;
}
    
}
text.innerHTML = CalculAge(month_birthday, year_i_was_born, month, year);

