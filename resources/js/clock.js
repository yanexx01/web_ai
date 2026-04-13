// function updateClock(){
//     const now = new Date();

//     const hours = String(now.getHours()).padStart(2, '0');
//     const minutes = String(now.getMinutes()).padStart(2, '0');
//     const seconds = String(now.getSeconds()).padStart(2, '0');
//     const timeString = `${hours}:${minutes}:${seconds}`;

//     const day = String(now.getDate()).padStart(2, '0');
//     const month = String(now.getMonth() + 1).padStart(2, '0'); // месяцы с 0
//     const year = now.getFullYear();
//     const dateString = `${day}.${month}.${year}`;

//     const daysOfWeek = [ 'Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
//     const dayOfWeek = daysOfWeek[now.getDay()];

//     const fullDateTime = `${timeString}\n${dateString} ${dayOfWeek}`;

//     const clockElement = document.getElementById('clock');
//     if (clockElement) {
//         clockElement.textContent = fullDateTime;
//     }
// }

// document.addEventListener('DOMContentLoaded', function () {
//     updateClock();
//     setInterval(updateClock, 1000);
// });

function updateClock() {
    const now = new Date();

    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const timeString = `${hours}:${minutes}:${seconds}`;

    const day = String(now.getDate()).padStart(2, '0');
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const year = now.getFullYear();
    const dateString = `${day}.${month}.${year}`;

    const daysOfWeek = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
    const dayOfWeek = daysOfWeek[now.getDay()];

    const fullDateTime = `${timeString}\n${dateString} ${dayOfWeek}`;

    const $clockElement = $('#clock');
    if ($clockElement.length) {
        $clockElement.text(fullDateTime);
    }
}

$(function() {
    updateClock();
    setInterval(updateClock, 1000);
});