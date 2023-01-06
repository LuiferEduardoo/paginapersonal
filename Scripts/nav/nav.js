const menuMobileIcon = document.querySelector('.menu_MobileIcon').addEventListener('click', toggleMenuMobileIcon);
const menuMobile = document.querySelector('.menu_Mobile');

function toggleMenuMobileIcon()
{
    menuMobile.classList.toggle('show');
}