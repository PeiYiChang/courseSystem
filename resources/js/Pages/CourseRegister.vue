<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Table from '@/Components/Table.vue';
import WatchList from '@/Components/WatchList.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { NCollapse } from 'naive-ui';
import axios from 'axios';
import { ref, onMounted } from 'vue';

// check if student is already enrolled in the class
const has_course = ref(false);
const searched = ref(false);

// TO BE UPDATED: Filter area and Courses Index area can be seperated to two components => ease up the readability
const form = useForm({
    courseID: ''
});

// const submit = () => {
//     form.post(route('course.filter'), {
//         onSuccess: () => form.reset(),
//         //onFailure: () => form.reset()
//     });
// };
// const props = defineProps({
//     watchListData: Array // The filtered courses passed from the controller
// });
// const submit = () => {
//     form.get(route('watchlist.index'), {
//         onSuccess: () => form.reset(),
//     });
// };
const clear = () => {
    form.reset();
};

const updateWatchList = () => {
    console.log('Updating watchlist...');
    get(route('watchlist.index'))
    
};

// const watchListData = ref([]);

// onMounted(() => {
//     axios.get(route('watchlist.index'))
//     .then(response => {
//             // Check if response.data is an array
//             if (Array.isArray(response.data)) {
//                 watchListData.value = response.data.map(course => ({
//                     label: course.courseTitle, // Adjust the key to match your structure
//                     key: course.courseID
//                 }));
//             }else{
//                 console.log("not an array, response data:", response.data);
//             }
//         })
//         .catch(error => {
//             console.error("Error fetching watchlist data:", error);
//         });
// });


</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Course Management
            </h2>
        </template>
        <form @submit.prevent="submit">
            <div class="py-9">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <!-- Finder Board -->
                        <h2 class="text-lg font-medium text-gray-900 px-6 py-6">
                            Register/Deregister your Courses
                        </h2>
                        <div class="px-6 text-gray-900">
                            Use this page to register for new courses or deregister from courses you're enrolled
                            in.<br />
                            Simply search for available courses to add, or view your current courses to remove them if
                            needed.
                        </div>

                        <div class="p-6 text-gray-900">
                            <div class="flex flex-wrap justify-start gap-x-8">
                                <!-- Course ID Input -->
                                <div class="flex-none w-64 min-w-[150px]">
                                    <InputLabel for="courseID" value="Course ID" />
                                    <TextInput id="courseID" type="text" class="mt-1 block w-full"
                                        placeholder="Course ID" v-model="form.courseID" />
                                </div>

                            </div>

                            <div class="flex justify-start gap-x-3 gap-y-8 mt-5">
                                <SecondaryButton
                                v-if="!searched"
                                class="mt-1 block w-1/7"
                                :class="{ 'opacity-25': form.processing }" 
                                :disabled="form.processing"
                                @click = "submit"
                                >
                                Search
                                </SecondaryButton>
                                
                                <DangerButton 
                                v-if="searched && has_course" 
                                class="mt-1 block w-1/7"
                                :class="{ 'opacity-25': form.processing }" 
                                :disabled="form.processing"
                                >
                                Drop
                                </DangerButton>

                                <PrimaryButton 
                                v-else-if="searched && !has_course" 
                                class="mt-1 block w-1/7"
                                :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Add
                                </PrimaryButton>
                                
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </form>
        <!-- show student time table -->
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 pb-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <n-collapse>
                    <n-collapse-item title="My Schedule" name="1" class="text-lg font-medium text-gray-900 p-6">
                    <Table/>
                    <!-- <DataTable :data="courses" /> -->
                </n-collapse-item>
                </n-collapse>
                </div>
            </div>

            <!-- show Watch list courses -->
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 pb-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <n-collapse :on-item-header-click="updateWatchList"	>
                    <n-collapse-item title="My Watch List" name="2" class="text-lg font-medium text-gray-900 p-6">
                    <!-- <Table/> -->
                    <WatchList />
                </n-collapse-item>
                </n-collapse>
                </div>
            </div>
        

    </AuthenticatedLayout>
</template>
