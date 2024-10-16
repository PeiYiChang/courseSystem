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
                        <!-- Finder Board Section -->
                        <div class="p-6 text-gray-900">
                            This is finder board
                        </div>

                        <!-- Form Section with aligned and spaced inputs -->
                        <div class="p-6 text-gray-900">
                            <div class="flex justify-start gap-x-8">
                                <!-- Course ID Input -->
                                <div>
                                    <InputLabel for="courseID" value="Course ID" />
                                    <TextInput id="courseID" type="text" class="mt-1 block w-full"
                                        placeholder="Course ID" v-model="form.courseID" />
                                </div>

                                <!-- Course Title Input -->
                                <div>
                                    <InputLabel for="courseTitle" value="Course Title" />
                                    <TextInput id="courseTitle" type="text" class="mt-1 block w-full"
                                        placeholder="Course Title" v-model="form.courseTitle" />
                                </div>

                                <!-- Course Instructor Input -->
                                <div>
                                    <InputLabel for="courseInstructor" value="Course Instructor" />
                                    <TextInput id="courseInstructor" type="text" class="mt-1 block w-full"
                                        placeholder="Course Instructor" v-model="form.courseInstructor" />
                                </div>


                            </div>

                            <div class="flex justify-start gap-x-8 gap-y-8">
                                <!-- Course Day Input -->
                                <div>
                                    <InputLabel for="courseDay" value="Course Day" />
                                    <TextInput id="courseDay" type="text" class="mt-1 block w-full"
                                        placeholder="Course Day" v-model="form.courseDay" />
                                </div>
                                <div>
                                    <InputLabel for="courseDay" value="Course Day" />
                                    <select id="courseDay" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        v-model="form.courseDay">

                                        <option value="1">Monday</option>
                                        <option value="2">Tuesday</option>
                                        <option value="3">Wednesday</option>
                                        <option value="4">Thursday</option>
                                        <option value="5">Friday</option>
                                        <option value="6">Saturday</option>
                                        <option value="7">Sunday</option>
                                    </select>
                                </div>

                                
                            </div>
                            <div class="flex justify-start gap-x-8 gap-y-8">
                                <!-- Course Period Input -->
                                <div>
                                    <InputLabel for="coursePeriod" value="Course Period" />
                                    <TextInput id="coursePeriod" type="text" class="mt-1 block w-full"
                                        placeholder="Course Period" v-model="form.coursePeriod" />
                                </div>
                                <div>
                                    <InputLabel for="coursePeriod" value="Course Period" />
                                    <select id="coursePeriod" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        v-model="form.coursePeriod">

                                        <option value="1">1 (08:10-09:00)</option>
                                        <option value="2">2 (09:10-10:00)</option>
                                        <option value="3">3 (10:10-11:00)</option>
                                        <option value="4">4 (11:10-12:00)</option>
                                        <option value="5">5 (12:10-13:00)</option>
                                        <option value="6">6 (13:10-14:00)</option>
                                        <option value="7">7 (14:10-15:00)</option>
                                        <option value="8">8 (15:10-16:00)</option>
                                        <option value="9">9 (16:10-17:00)</option>
                                        <option value="10">10 (17:10-18:00)</option>
                                        <option value="11">11 (18:30-19:20)</option>
                                        <option value="12">12 (19:25-20:15)</option>
                                        <option value="13">13 (20:25-21:15)</option>
                                        <option value="14">14 (21:20-22:10)</option>
                                    </select>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </AuthenticatedLayout>
</template>
