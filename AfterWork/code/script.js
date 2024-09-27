// Fonction pour activer ou désactiver le mode sombre
const darkModeToggle = document.getElementById('darkModeToggle');
const body = document.body;
const logo = document.getElementById('logo');

darkModeToggle.addEventListener('click', () => {
    body.classList.toggle('dark-mode');

    // Changer le texte du bouton en fonction du mode
    if (body.classList.contains('dark-mode')) {
        darkModeToggle.textContent = "Désactiver le mode sombre";
    } else {
        darkModeToggle.textContent = "Activer le mode sombre";
    }
});
