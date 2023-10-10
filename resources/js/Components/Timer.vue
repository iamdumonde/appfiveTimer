<script setup>
import { reactive } from "vue";

const props = defineProps(['quiz']);

let duration = parseInt(props.quiz.duration);
let state = reactive({
    min: duration - 1,
    secondes: 59,
    cent: 99,
    intervalID: "",
});

function startQuizz() {
    state.intervalID = setInterval(() => {
        if (state.min > -1) {
            state.cent--;
            if (state.cent === 0) {
                state.cent = 99;
                state.secondes--;
            } else if (state.secondes === 0) {
                state.secondes = 60;
                state.min--;
            }
        } else if (state.min <= -1) {
            clearInterval(state.intervalID);
            state.min = 0;
            state.cent = 0;
            state.secondes = 0;
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


</script>

<!-- <style scoped>
.blinking {
    animation: blinker 1s linear infinite;
}

@keyframes blinker {
    50% {
        opacity: 0;
    }
}

</style> -->

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
        </div>

    </div>
</template>