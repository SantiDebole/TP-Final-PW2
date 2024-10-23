document.getElementById('editarBtn').addEventListener('click', function() {
    const inputs = document.querySelectorAll('.informacion input, .informacion select');
    const guardarBtn = document.getElementById('guardarBtn');

    inputs.forEach(input => {
        input.disabled = false; // Habilitar campos
    });

    guardarBtn.disabled = false; // Habilitar botón de guardar
    this.disabled = true; // Deshabilitar botón de editar
});

document.getElementById('guardarBtn').addEventListener('click', function() {
    const inputs = document.querySelectorAll('.informacion input, .informacion select');
    const editarBtn = document.getElementById('editarBtn');
    const fotoInput = document.getElementById('fotoInput');
    const fotoPerfil = document.getElementById('fotoPerfil');

    // Si se ha seleccionado una nueva imagen, actualizar la foto de perfil
    if (fotoInput.files && fotoInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            fotoPerfil.src = e.target.result; // Actualizar la imagen
        }
        reader.readAsDataURL(fotoInput.files[0]); // Leer la imagen
    }

    inputs.forEach(input => {
        input.disabled = true; // Deshabilitar campos
    });

    this.disabled = true; // Deshabilitar botón de guardar
    editarBtn.disabled = false; // Habilitar botón de editar
    alert('Información guardada.'); // Simulación de guardar
});
