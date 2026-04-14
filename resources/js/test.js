// document.addEventListener('DOMContentLoaded', function () {
//     var form = document.getElementById('testForm');

//     form.addEventListener('submit', function (e) {
//         var fullName = document.getElementById('fullName');
//         var group = document.getElementById('group');
//         var q1 = document.getElementById('q1');
//         var q2Radios = document.getElementsByName('Вопрос_2');
//         var q3 = document.getElementById('q3');

//         var firstEmpty = null;
//         var errorMessage = '';

//         if (!fullName.value.trim()) {
//             firstEmpty = fullName;
//             errorMessage = 'Поле "ФИО" обязательно для заполнения.';
//         } else {
//             var fioWords = fullName.value.trim().split(/\s+/);
//             if (fioWords.length !== 3) {
//                 firstEmpty = fullName;
//                 errorMessage = 'ФИО должно состоять из трёх слов: Фамилия, Имя, Отчество.';
//             }
//         }

//         if (!firstEmpty) {
//             if (group.value === "") {
//                 firstEmpty = group;
//                 errorMessage = 'Пожалуйста, выберите группу.';
//             }
//         }

//         if (!firstEmpty) {
//             if (!q1.value.trim()) {
//                 firstEmpty = q1;
//                 errorMessage = 'Ответ на вопрос 1 обязателен.';
//             } else {
//                 var answerWords = q1.value.trim().split(/\s+/);
//                 if (answerWords.length < 30) {
//                     firstEmpty = q1;
//                     errorMessage = `Ответ должен содержать не менее 30 слов. Сейчас: ${answerWords.length}.`;
//                 }
//             }
//         }

//         if (!firstEmpty) {
//             var isQ2Selected = Array.from(q2Radios).some(r => r.checked);
//             if (!isQ2Selected) {
//                 firstEmpty = q2Radios[0];
//                 errorMessage = 'Пожалуйста, выберите ответ на вопрос 2.';
//             }
//         }

//         if (!firstEmpty) {
//             if (q3.value === "") {
//                 firstEmpty = q3;
//                 errorMessage = 'Пожалуйста, выберите ответ на вопрос 3.';
//             }
//         }

//         if (firstEmpty) {
//             e.preventDefault();
//             alert(errorMessage);
//             firstEmpty.focus();
//         }
//     });
// });

$(function() {
    const $form = $('#testForm');

    $form.on('submit', function(e) {
        const $fullName = $('#fullName');
        const $group = $('[name="user_group"]');
        const $q1 = $('#q1');
        const $q2Radios = $('input[name="Вопрос_2"]');
        const $q3 = $('#q3');

        let $firstEmpty = null;
        let errorMessage = '';

        const fullNameVal = $fullName.val().trim();
        if (!fullNameVal) {
            $firstEmpty = $fullName;
            errorMessage = 'Поле "ФИО" обязательно для заполнения.';
        } else {
            const fioWords = fullNameVal.split(/\s+/);
            if (fioWords.length !== 3) {
                $firstEmpty = $fullName;
                errorMessage = 'ФИО должно состоять из трёх слов: Фамилия, Имя, Отчество.';
            }
        }

        if (!$firstEmpty && !$group.val()) {
            $firstEmpty = $group;
            errorMessage = 'Пожалуйста, выберите группу.';
        }

        if (!$firstEmpty) {
            const q1Val = $q1.val().trim();
            if (!q1Val) {
                $firstEmpty = $q1;
                errorMessage = 'Ответ на вопрос 1 обязателен.';
            } else {
                const answerWords = q1Val.split(/\s+/);
                if (answerWords.length < 30) {
                    $firstEmpty = $q1;
                    errorMessage = `Ответ должен содержать не менее 30 слов. Сейчас: ${answerWords.length}.`;
                }
            }
        }

        if (!$firstEmpty) {
            const isQ2Selected = $q2Radios.is(':checked');
            if (!isQ2Selected) {
                $firstEmpty = $q2Radios.first();
                errorMessage = 'Пожалуйста, выберите ответ на вопрос 2.';
            }
        }

        if (!$firstEmpty && !$q3.val()) {
            $firstEmpty = $q3;
            errorMessage = 'Пожалуйста, выберите ответ на вопрос 3.';
        }

        if ($firstEmpty) {
            e.preventDefault();
            alert(errorMessage);
            $firstEmpty.focus();
        }
    });
});