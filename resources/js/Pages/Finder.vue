<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    courseID: '',
    courseTitle: '',
    courseInstructor: '',
    courseDay: '',
    coursePeriod: '',
});

// const submit () => {
//     form.post(route(), {
//         //onFinish: () 
//     })
// };
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Explore Courses
            </h2>
        </template>
        <form @submit.prevent="submit">
            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            This is finder board
                        </div>
                        <!-- Course ID Input -->
                        <div class="mt-4">
                            <InputLabel for="courseID" value="Course ID" />

                            <TextInput 
                                id="courseID" 
                                type="text" 
                                class="mt-1 block w-full" 
                                placeholder="Course ID"
                                v-model="form.courseID"
                            />
                            
                            
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
