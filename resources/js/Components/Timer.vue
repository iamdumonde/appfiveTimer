<script setup>
import { reactive, watch } from "vue";

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


// ===============================================================
// let state = reactive({
//     inputRangeValue: 1,
//     min: duration - 1,
//     secondes: 59,
//     cent: 99,
//     timeoutID: "",
// });

// // function to ly theb timer with a input range
// function moveTheRange(){
//     state.inputRangeValue = Math.floor((state.secondes / 60) * 100);
// }

// function startQuizz() {
//     state.timeoutID = setTimeout(() => {
//         if (state.min > -1) {
//             state.cent--;
//             moveTheRange();
//             if (state.cent === 0) {
//                 state.cent = 99;
//                 state.secondes--;
//                 moveTheRange();
//             } else if (state.secondes === 0) {
//                 state.secondes = 60;
//                 state.min--;
//                 moveTheRange();
//             }
//         } else if (state.min <= -1) {
//             clearTimeout(state.timeoutID);
//             state.min = 0;
//             state.cent = 0;
//             state.secondes = 0;
//             moveTheRange();
//             // console.log('clearInterval');
//         }
//         startQuizz();
//     }, 10);

// }
// watch(() => state.secondes, moveTheRange);

// function doubleNum(number) {
//     if (number < 10) {
//         return '0' + number;
//     } else {
//         return number;
//     }
// }
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

            <button @click="startQuizz()" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Chrono</button>
            <input type="range" name="" id=""
            v-model="state.inputRangeValue"
            class="w-full"
            >
            {{ state.inputRangeValue }}
        </div>

    </div>
</template>