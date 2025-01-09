//подсчет времени 
let startTime; 

function initTimer() {
    startTime = Date.now();
}

function calculateTime() {
    const endTime = Date.now(); 
    const timeSpent = endTime - startTime; 
    const over30 = timeSpent / 1000 > 30 ? '1' : '0';
    document.getElementById('input30Second').setAttribute("value", over30)
}

window.onload = initTimer;
