## Part One

Il semble que le problème réside dans le fait que le minuteur est basé sur le temps d'exécution de la page. Lorsque vous quittez la page du quiz, le code JavaScript cesse de s'exécuter et le minuteur est mis en pause.

Pour résoudre ce problème, vous devrez envisager une approche différente. Voici quelques suggestions :

1. **Utilisation de sessionStorage ou localStorage** : Vous pouvez stocker le temps restant dans `sessionStorage` ou `localStorage`. Ainsi, même si l'utilisateur quitte la page et y revient, vous pouvez récupérer le temps restant à partir du stockage local et continuer à partir de là.

Voici un exemple de code qui illustre cette approche :

```javascript
// Au début du minuteur
sessionStorage.setItem('quizTime', JSON.stringify({ min: state.min, secondes: state.secondes, cent: state.cent }));

// Pour récupérer le temps restant
const savedTime = JSON.parse(sessionStorage.getItem('quizTime'));
if (savedTime) {
  state.min = savedTime.min;
  state.secondes = savedTime.secondes;
  state.cent = savedTime.cent;
}

// Au moment de quitter la page ou de mettre en pause le quiz
sessionStorage.removeItem('quizTime');
```

2. **Gestion côté serveur** : Vous pouvez gérer le minuteur côté serveur et envoyer des mises à jour au client à intervalles réguliers. Ainsi, même si le client quitte la page et y revient, le minuteur continuera de fonctionner.

3. **Utilisation de Web Workers** : Les Web Workers permettent d'exécuter du code JavaScript dans un thread séparé. Cela signifie que même si la page est inactive, le Web Worker continue de s'exécuter. Cependant, cela peut être un peu plus complexe à mettre en place.

Choisissez l'approche qui convient le mieux à votre application en fonction de vos besoins et de vos contraintes techniques. Assurez-vous également de gérer correctement les cas où le quiz est terminé ou interrompu par l'utilisateur.

## Part Two
Je comprends mieux votre problème maintenant. Pour faire en sorte que le minuteur continue de fonctionner même lorsque l'utilisateur ouvre un nouvel onglet ou quitte temporairement la page, vous pouvez utiliser l'événement `beforeunload` pour sauvegarder l'état du minuteur.

Voici comment vous pouvez procéder :

```javascript
import { onBeforeUnmount, onMounted, ref } from "vue";

const props = defineProps(['quiz']);

let duration = parseInt(props.quiz.duration);
let state = ref({
    min: duration - 1,
    secondes: 59,
    cent: 99,
    timeoutID: "",
});

// Sauvegarde l'état du minuteur avant de quitter la page
window.addEventListener("beforeunload", () => {
    localStorage.setItem("quizState", JSON.stringify(state.value));
});

// Récupère l'état du minuteur au chargement de la page
onMounted(() => {
    const savedState = JSON.parse(localStorage.getItem("quizState"));
    if (savedState) {
        state.value = savedState;
    }
});

// Supprime l'état du minuteur lorsque le composant est détruit
onBeforeUnmount(() => {
    localStorage.removeItem("quizState");
});

function startQuizz() {
    state.value.timeoutID = setTimeout(() => {
        // Votre logique pour le minuteur reste la même
    }, 0.1);
}

function doubleNum(number) {
    // Votre fonction doubleNum
}

export { state, startQuizz, doubleNum };
```

Dans ce code, j'ai utilisé `localStorage` pour stocker et récupérer l'état du minuteur. Lorsque l'utilisateur quitte la page (`beforeunload`), l'état actuel du minuteur est sauvegardé. Au chargement de la page (`onMounted`), nous vérifions s'il y a un état sauvegardé et le chargeons si c'est le cas. Lorsque le composant est détruit (`onBeforeUnmount`), nous supprimons l'état du minuteur du stockage local.

Ainsi, même si l'utilisateur ouvre un nouvel onglet ou quitte temporairement la page, le minuteur continuera de fonctionner à partir de l'état sauvegardé.


## Part three

Je comprends, vous souhaitez que le minuteur continue de fonctionner en arrière-plan, même si l'utilisateur passe à un autre onglet ou une autre application. Pour atteindre cet objectif, vous devrez utiliser les Web Workers pour exécuter le minuteur dans un thread séparé.

Voici comment vous pouvez faire cela :

1. Créez un nouveau fichier appelé `timerWorker.js` avec le contenu suivant :

```javascript
let min = 0;
let secondes = 0;
let cent = 0;

let timeoutID;

self.onmessage = function(event) {
    if (event.data === 'start') {
        startTimer();
    } else if (event.data === 'stop') {
        clearTimeout(timeoutID);
    }
};

function startTimer() {
    timeoutID = setTimeout(function() {
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
        }
    }, 10);
}
```

2. Dans votre composant Vue, vous devrez créer un Web Worker pour gérer le minuteur. Voici comment vous pouvez faire :

```javascript
import { onBeforeUnmount, onMounted, ref } from "vue";

const props = defineProps(['quiz']);

let duration = parseInt(props.quiz.duration);

let state = ref({
    min: duration - 1,
    secondes: 59,
    cent: 99,
});

let timerWorker = new Worker('path/to/timerWorker.js');

timerWorker.onmessage = function(event) {
    state.value = event.data;
};

onMounted(() => {
    timerWorker.postMessage('start');
});

onBeforeUnmount(() => {
    timerWorker.postMessage('stop');
});

function doubleNum(number) {
    if (number < 10) {
        return '0' + number;
    } else {
        return number;
    }
}

export { state, doubleNum };
```

Dans ce code, nous avons créé un fichier `timerWorker.js` qui contient le code du minuteur. Puis, dans le composant Vue, nous créons un Web Worker à partir de ce fichier. Le Web Worker gère le minuteur et communique avec le composant pour mettre à jour l'état du minuteur.

Lorsque le composant est monté (`onMounted`), nous envoyons un message au Web Worker pour démarrer le minuteur. Lorsque le composant est démonté (`onBeforeUnmount`), nous envoyons un message au Web Worker pour arrêter le minuteur.

Cela devrait permettre au minuteur de continuer à fonctionner en arrière-plan, même si l'utilisateur passe à un autre onglet ou une autre application.

## Part Four 
Bien sûr, je vais expliquer le code en détail.

**timerWorker.js**:

1. Dans ce fichier, nous créons un nouveau thread de travail (Web Worker) qui exécute du code JavaScript dans un contexte distinct. Cela signifie qu'il fonctionne en parallèle avec le thread principal de l'application.

2. Ensuite, nous définissons trois variables : `min`, `secondes`, et `cent` qui représentent les composantes du temps.

3. Nous déclarons une variable `timeoutID` qui sera utilisée pour stocker l'ID du délai d'exécution. Cela nous permettra d'arrêter le minuteur si nécessaire.

4. Ensuite, nous configurons un gestionnaire d'événements (`onmessage`) qui sera appelé chaque fois que le thread principal envoie un message au Web Worker.

5. Si le message envoyé est `'start'`, le Web Worker commence à exécuter la fonction `startTimer`.

6. La fonction `startTimer` est une boucle récursive qui décrémente les composantes du temps (`cent`, `secondes`, `min`) à chaque itération, simulant ainsi le décompte du minuteur.

7. Si `min` est supérieur ou égal à zéro, le minuteur continue de compter.

**Le composant Vue**:

1. Dans le composant Vue, nous importons les fonctionnalités nécessaires de Vue (`onBeforeUnmount`, `onMounted`, `ref`) et définissons les propriétés (`props`) nécessaires.

2. Nous créons une référence (`state`) qui contiendra l'état actuel du minuteur. Cette référence est initialement définie en fonction de la durée du quiz.

3. Nous créons un nouvel objet `timerWorker` qui représente le Web Worker. Nous lui donnons le chemin vers le fichier `timerWorker.js` qui contient le code pour le minuteur.

4. Nous configurons un gestionnaire d'événements (`onmessage`) pour le Web Worker. Chaque fois que le Web Worker envoie un message, le gestionnaire d'événements est appelé. Dans notre cas, il met à jour l'état du minuteur dans `state`.

5. Lorsque le composant est monté (`onMounted`), nous envoyons un message au Web Worker en utilisant `timerWorker.postMessage('start')`. Cela déclenche le démarrage du minuteur.

6. Lorsque le composant est démonté (`onBeforeUnmount`), nous envoyons un message au Web Worker en utilisant `timerWorker.postMessage('stop')`. Cela arrête le minuteur.

7. La fonction `doubleNum` semble être une fonction auxiliaire qui renvoie un nombre formaté avec un zéro devant s'il est inférieur à 10. Cependant, cette fonction n'est pas utilisée dans le code que vous avez fourni.

En résumé, ce code utilise un Web Worker (`timerWorker.js`) pour exécuter le minuteur en arrière-plan. Le composant Vue communique avec le Web Worker pour démarrer et arrêter le minuteur, et met à jour l'état du minuteur en fonction des messages reçus du Web Worker.

## Part five
Vous avez raison, il semble y avoir une lacune dans le code. Le minuteur continue indéfiniment même après que le décompte a pris fin. Pour résoudre cela, nous devons ajouter une vérification pour arrêter le minuteur lorsque le décompte est terminé.

Voici comment vous pouvez faire cela :

Dans le fichier `timerWorker.js`, ajoutez une vérification pour arrêter le minuteur lorsque `min` est inférieur à zéro :

```javascript
function startTimer() {
    timeoutID = setTimeout(function() {
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
            self.postMessage({ min, secondes, cent, finished: true }); // Envoyer un message indiquant que le décompte est terminé
        }
    }, 10);
}
```

Dans le composant Vue, mettez à jour le gestionnaire d'événements pour traiter le message indiquant que le décompte est terminé et arrêter le minuteur :

```javascript
timerWorker.onmessage = function(event) {
    if (event.data.finished) {
        // Le décompte est terminé, faites ce que vous devez faire ici
    } else {
        state.value = event.data;
    }
};
```

Avec ces modifications, lorsque le décompte atteint zéro, le Web Worker enverra un message au composant Vue pour indiquer que le décompte est terminé. Vous pouvez alors prendre les mesures nécessaires pour gérer la fin du décompte.

## Part six
Vous avez raison, et j'apprécie votre patience. Si vous souhaitez arrêter le minuteur lorsque le décompte est terminé, vous pouvez effectivement utiliser `clearTimeout`.

Dans le fichier `timerWorker.js`, vous pouvez mettre à jour la fonction `startTimer` comme suit :

```javascript
function startTimer() {
    timeoutID = setTimeout(function() {
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
            clearTimeout(timeoutID); // Arrête le minuteur
            self.postMessage({ min, secondes, cent, finished: true }); // Envoyer un message indiquant que le décompte est terminé
        }
    }, 10);
}
```

Cela permettra d'arrêter le minuteur lorsque le décompte atteint zéro.

Dans le composant Vue, vous pouvez alors gérer le message pour prendre des mesures appropriées lorsque le décompte est terminé :

```javascript
timerWorker.onmessage = function(event) {
    if (event.data.finished) {
        // Le décompte est terminé, faites ce que vous devez faire ici
    } else {
        state.value = event.data;
    }
};
```

Cela devrait résoudre le problème de l'arrêt du minuteur lorsque le décompte est terminé. Merci de votre patience et je m'excuse pour la confusion précédente. Si vous avez d'autres questions ou besoin de plus d'assistance, n'hésitez pas à me le faire savoir.


## Part  ******

D'accord, voici comment vous pouvez procéder :

1. Dans votre script, définissez une variable pour suivre l'état du minuteur :

```javascript
<script setup>
import { ref } from 'vue';

let isTimerRunning = ref(false);

// ... Votre code existant ...

function startTimer() {
    isTimerRunning.value = true;
    // ... Votre code pour démarrer le minuteur ...
}

</script>
```

2. Dans votre template, utilisez la directive `:class` pour ajouter la classe conditionnellement lorsque le minuteur est en cours d'exécution :

```html
<template>
    <div class="schowChrono flex flex-col items-center">
        <span :class="{'timer-running': isTimerRunning}" class="text-3xl mb-4 timer">
            Affiche moi le chrono : {{ doubleNum(state.min) }} : {{ doubleNum(state.secondes) }} : {{ doubleNum(state.cent) }}
        </span>
    </div>

    <button @click="startTimer" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        Chrono
    </button>
</template>
```

3. Ajoutez le CSS pour votre classe `timer-running` dans votre fichier CSS ou dans votre balise `<style>` :

```css
<style>
.timer-running {
    /* Styles pour le chrono en cours d'exécution */
    /* Par exemple, vous pouvez ajouter une couleur de texte différente ou un fond différent */
    color: #FF0000; /* Couleur de texte rouge */
    /* Ajoutez d'autres styles au besoin */
}
</style>
```

Avec cette configuration, lorsque vous cliquez sur le bouton "Chrono" et que le minuteur démarre, la classe `timer-running` sera ajoutée à l'élément `<span>` qui affiche le chronomètre. Vous pouvez personnaliser les styles de la classe `timer-running` selon vos préférences.