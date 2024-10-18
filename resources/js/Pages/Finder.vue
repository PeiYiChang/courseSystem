<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Table from '@/Components/Table.vue';
import DataTable from '@/Pages/DataTable.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';




// TO BE UPDATED: Filter area and Courses Index area can be seperated to two components => ease up the readability
const form = useForm({
    courseID: '',
    courseTitle: '',
    courseInstructor: '',
    courseDay: '',
    coursePeriod: '',
});

const submit = () => {
    form.post(route('course.filter'), {
        onSuccess: () => form.reset(),
    });
};

const clear = () => {
    form.reset();
};

const props = defineProps({
    courses: Array // The filtered courses passed from the controller
});

</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Finder
            </h2>
        </template>
        <form @submit.prevent="submit">
            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <!-- Finder Board -->
                        <h2 class="text-lg font-medium text-gray-900 p-6">
                            Explore Courses
                        </h2>
                        <div class="px-6 text-gray-900">
                            Use the form below to search and explore available courses.<br />
                            Fill in the details to find courses that match your interests or schedule.
                        </div>

                        <div class="p-6 text-gray-900">
                            <div class="flex flex-wrap justify-start gap-x-8">
                                <!-- Course ID Input -->
                                <div class="flex-none w-64 min-w-[150px]">
                                    <InputLabel for="courseID" value="Course ID" />
                                    <TextInput id="courseID" type="text" class="mt-1 block w-full"
                                        placeholder="Course ID" v-model="form.courseID" />
                                </div>

                                <!-- Course Title Input -->
                                <div class="flex-none w-64 min-w-[150px]">
                                    <InputLabel for="courseTitle" value="Course Title" />
                                    <TextInput id="courseTitle" type="text" class="mt-1 block w-full"
                                        placeholder="Course Title" v-model="form.courseTitle" />
                                </div>

                                <!-- Course Instructor Input -->
                                <div class="flex-none w-64 min-w-[150px]">
                                    <InputLabel for="courseInstructor" value="Course Instructor" />
                                    <TextInput id="courseInstructor" type="text" class="mt-1 block w-full"
                                        placeholder="Course Instructor" v-model="form.courseInstructor" />
                                </div>


                            </div>

                            <div class="flex flex-wrap justify-start gap-x-8 gap-y-8 mt-5 mb-2">
                                <!-- Course Day Input -->
                                <div class="flex-none w-64 min-w-[150px]">
                                    <InputLabel for="courseDay" value="Course Day" />
                                    <select id="courseDay" type="text"
                                        class="mt-1 block w-full p-2 border border-gray-300 rounded min-w-[186px]"
                                        v-model="form.courseDay">

                                        <!-- <option value="" disabled selected>Select a day</option> -->
                                        <option value="1">Monday</option>
                                        <option value="2">Tuesday</option>
                                        <option value="3">Wednesday</option>
                                        <option value="4">Thursday</option>
                                        <option value="5">Friday</option>
                                        <option value="6">Saturday</option>
                                        <option value="7">Sunday</option>
                                    </select>
                                </div>

                                <!-- Course Period Input -->
                                <div class="flex-none w-64 min-w-[150px]">
                                    <InputLabel for="coursePeriod" value="Course Period" />
                                    <TextInput id="coursePeriod" type="text" class="mt-1 block w-full"
                                        placeholder="Course Period" v-model="form.coursePeriod" />
                                </div>
                                <div class="flex-none w-64 min-w-[150px]">
                                    <InputLabel for="coursePeriod" value="Course Period" />
                                    <select id="coursePeriod" type="text"
                                        class="mt-1 block w-full p-2 border border-gray-300 rounded min-w-[186px]"
                                        v-model="form.coursePeriod">

                                        <!-- <option value="" disabled selected>Select a day</option> -->
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
                            <div class="flex justify-start gap-x-3 gap-y-8 mt-9">
                                <PrimaryButton class="mt-1 block w-1/7" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">
                                    Filter
                                </PrimaryButton>

                                <DangerButton class="mt-1 block w-1/7" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing" @click="clear">
                                    Clear
                                </DangerButton>
                            </div>
                        </div>
                    </div>

                </div>
                
            </div>
            <!-- show filtered course info -->
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 pb-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                        <h2 class="text-lg font-medium text-gray-900 p-6">
                            Filtered Courses
                        </h2>
                        <!-- <Table/> -->
                        <DataTable :data="courses" />
                </div>
            </div>
        </form>

    </AuthenticatedLayout>
</template>
