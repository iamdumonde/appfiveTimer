<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Timer from '@/Components/Timer.vue';

defineProps(['quizzes']);

const form = useForm({
    message: '',
    duration: ''
});

</script>

<template>
    <AppLayout title="QuizForm">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                QuizForm
            </h2>
        </template>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <form @submit.prevent="form.post(route('quizs.store'), { onSuccess: () => form.reset() })">
                <textarea
                    v-model="form.message"
                    placeholder="What's on your mind?"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                ></textarea>

                <select 
                    v-model="form.duration"
                    class="form-select my-2 px-4 py-3 rounded-md"
                    name="" id=""> 
                    <option value="" desabled>Choisir</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                </select>
                <div class="my-2 px-10 py-4 bg-gray-100 border-t border-gray-100 rounded-md shadow-sm">
                  <PrimaryButton>Let's go</PrimaryButton>
                </div>
            </form>

            <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                <Timer
                    v-for="quiz in quizzes"
                    :key="quiz.id"
                    :quiz="quiz"
                />
            </div>
        </div>
    </AppLayout>
</template>