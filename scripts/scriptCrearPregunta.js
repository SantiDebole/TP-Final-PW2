// const questions = []; // Aquí almacenarás las preguntas
//
// document.getElementById('submitBtn').addEventListener('click', () => {
//     const newQuestion = document.getElementById('newQuestion').value;
//     const answers = [
//         document.getElementById('answer1').value,
//         document.getElementById('answer2').value,
//         document.getElementById('answer3').value,
//         document.getElementById('answer4').value,
//     ];
//
//     if (newQuestion && answers.every(a => a)) {
//         questions.push({
//             question: newQuestion,
//             answers: answers,
//             correct: 0 // Aquí puedes definir la respuesta correcta
//         });
//         openModal();
//     } else {
//         alert("Por favor, completa todos los campos.");
//     }
// });
//
// function openModal() {
//     document.getElementById('modal').style.display = "block";
// }
//
// document.getElementById('closeModal').onclick = function() {
//     closeModal();
// };
//
// document.getElementById('goBackBtn').addEventListener('click', () => {
//     closeModal();
//     // Aquí puedes agregar la lógica para volver al lobby
// });
//
// document.getElementById('newQuestionBtn').addEventListener('click', () => {
//     closeModal();
//     document.getElementById('newQuestion').value = '';
//     document.getElementById('answer1').value = '';
//     document.getElementById('answer2').value = '';
//     document.getElementById('answer3').value = '';
//     document.getElementById('answer4').value = '';
// });
//
// function closeModal() {
//     document.getElementById('modal').style.display = "none";
// }
//
// // Cierra el modal si se hace clic fuera de él
// window.onclick = function(event) {
//     const modal = document.getElementById('modal');
//     if (event.target === modal) {
//         closeModal();
//     }
// };

document.getElementById('submitBtn').addEventListener('click', () => {
    const newQuestion = document.getElementById('newQuestion').value;
    const answers = [
        { descripcion: document.getElementById('answer1').value, esCorrecta: 1 },
        { descripcion: document.getElementById('answer2').value, esCorrecta: 0 },
        { descripcion: document.getElementById('answer3').value, esCorrecta: 0 },
        { descripcion: document.getElementById('answer4').value, esCorrecta: 0 }
    ];

    if (newQuestion && answers.every(a => a.descripcion)) {
        fetch('http://localhost/trivia-master/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                pregunta: newQuestion,
                respuestas: answers
            })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                alert(data.message || data.error);
                if (data.message) {
                    openModal(); // Abre el modal solo si la pregunta se envió correctamente
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Error al enviar la pregunta: " + error.message);
            });
    } else {
        alert("Por favor, completa todos los campos.");
    }
});
