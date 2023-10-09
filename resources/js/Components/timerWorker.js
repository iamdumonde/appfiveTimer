let min = 0;
let secondes = 0;
let cent = 0;
let timeoutID;

self.onmessage = function (event) {
    if (event.data === 'start') {
        startTimer();
    } else if (event.data === 'stop') {
        clearTimeout(timeoutID);
    }
};

function startTimer() {
    console.log('Bonjour');
    timeoutID = setTimeout(() => {
        cent--;
        if (cent < 0) {
            cent = 99;
            secondes--;
        }
        if (secondes < 0) {
            secondes = 59;
            min--;
        }

        if (min >= 0) {
            startTimer();
        } else {
            clearTimeout(timeoutID);
            self.postMessage({ min, secondes, cent, finished: true });
        }
    }, 10);
}