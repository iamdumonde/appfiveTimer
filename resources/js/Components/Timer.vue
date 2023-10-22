<script setup>
import { onMounted } from 'vue';
import { reactive } from "vue";

const props = defineProps(['quiz']);
let duration = parseInt(props.quiz.duration);
let state = reactive({
    inputRangeValue: 0, // Démarre à 0
    min: duration ,
    secondes: 0,
    cent: 0,
    timeoutID: null,
});

function startQuizz() {
    if (state.timeoutID) return; // Évite de lancer plusieurs minuteries
    state.timeoutID = setInterval(() => {
        if (state.min > 0 || (state.secondes > 0 || state.cent > 0)) {
            if (state.cent === 0) {
                state.cent = 99;
                if (state.secondes === 0) {
                    state.secondes = 59;
                    state.min--;
                } else {
                    state.secondes--;
                }
                state.inputRangeValue = 100 - (state.min * 60 + state.secondes) / (duration * 60) * 100;
            } else {
                state.cent--;
            }
        } else {
            clearInterval(state.timeoutID);
            state.timeoutID = null;
        }
    }, 10);
}

function doubleNum(number) {
    if (number < 10) {
        return '0' + number;
    } else {
        return number;
    }
}

// onMounted(() => {
//   const inputRange = document.getElementById('range');
//   console.log(inputRange);
//   inputRange.addEventListener('click', () => {
//     const pourcentage = (inputRange.value - inputRange.min) / (inputRange.max - inputRange.min);
//     const couleur = `linear-gradient(to right, red ${pourcentage * 100}%, #ccc ${pourcentage * 100}%)`;
    
//     inputRange.style.background = couleur;
//   });
// });


</script>

<template>
    <div class="p-6 flex space-x-2">
        <div class="flex-1">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-gray-800">{{ quiz.user.name }}</span>
                    <p class="mt-4 text-lg text-gray-900">{{ quiz.message }}</p>
                    <span class="text-gray-800">Duration: {{ quiz.duration }}</span>
                    <small class="ml-2 text-sm text-gray-600">{{ new Date(quiz.created_at).toLocaleString() }}</small>
                </div>
            </div>
            <div class="schowChrono flex flex-col items-center">
                <span class="text-3xl mb-4 timer">
                    Affiche moi le chrono : {{ doubleNum(state.min) }} : {{ doubleNum(state.secondes) }} : {{
                        doubleNum(state.cent) }}
                </span>
            </div>

            <button @click="startQuizz" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Chrono</button>
            <input type="range" name="" id="range"
            v-model="state.inputRangeValue"
            class="w-full custom-input-range"
            >
            {{ state.inputRangeValue }}
        </div>

    </div>
</template>

<style>
input[type=range] {
    pointer-events: none;
}

/* Change la couleur de la piste lors du déplacement du slider */
/* Ajoute un dégradé si vous le souhaitez */
/* input[type="range"]::-webkit-slider-runnable-track {
    background: linear-gradient(to right, yellow, gold);
} */

/* Pour Firefox */
/* input[type="range"]::-moz-range-track {
    background: linear-gradient(to right, #votre_couleur, #votre_couleur);
} */


</style>