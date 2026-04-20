// function setCookie(name, value, expiration_days) {
//     const d = new Date();
//     d.setTime(d.getTime() + (expiration_days * 24 * 60 * 60 * 1000));
//     const expires = "expires=" + d.toUTCString();
//     document.cookie = name + "=" + encodeURIComponent(value) + ";" + expires + ";path=/";
// }

// function getCookie(name) {
//     const nameEQ = name + "=";
//     const ca = document.cookie.split(';');
//     for (let i = 0; i < ca.length; i++) {
//         let c = ca[i].trim();
//         if (c.indexOf(nameEQ) === 0) {
//             return decodeURIComponent(c.substring(nameEQ.length));
//         }
//     }
//     return null;
// }

// function incrementCounter(storageObj, pageName) {
//     storageObj[pageName] = (storageObj[pageName] || 0) + 1;
//     return storageObj;
// }

// function trackPageView(pageName) {
//     let sessionHistory = JSON.parse(localStorage.getItem('sessionHistory')) || {};
//     sessionHistory = incrementCounter(sessionHistory, pageName);
//     localStorage.setItem('sessionHistory', JSON.stringify(sessionHistory));

//     let allTimeHistory = JSON.parse(getCookie('allTimeHistory') || '{}');
//     allTimeHistory = incrementCounter(allTimeHistory, pageName);
//     setCookie('allTimeHistory', JSON.stringify(allTimeHistory), 365);
// }

function setCookie(name, value, expiration_days) {
    const d = new Date();
    d.setTime(d.getTime() + (expiration_days * 24 * 60 * 60 * 1000));
    const expires = "expires=" + d.toUTCString();
    document.cookie = name + "=" + encodeURIComponent(value) + ";" + expires + ";path=/";
}

function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i].trim();
        if (c.indexOf(nameEQ) === 0) {
            return decodeURIComponent(c.substring(nameEQ.length));
        }
    }
    return null;
}

function incrementCounter(storageObj, pageName) {
    storageObj[pageName] = (storageObj[pageName] || 0) + 1;
    return storageObj;
}

function trackPageView(pageName) {
    let sessionHistory = JSON.parse(localStorage.getItem('sessionHistory')) || {};
    sessionHistory = incrementCounter(sessionHistory, pageName);
    localStorage.setItem('sessionHistory', JSON.stringify(sessionHistory));

    let allTimeHistory = JSON.parse(getCookie('allTimeHistory') || '{}');
    allTimeHistory = incrementCounter(allTimeHistory, pageName);
    setCookie('allTimeHistory', JSON.stringify(allTimeHistory), 365);
}