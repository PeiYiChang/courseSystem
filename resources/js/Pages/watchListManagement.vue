<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Table from "@/Components/Table.vue";
import WatchList from "@/Components/WMwatchList.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { NCollapse } from "naive-ui";
import axios from "axios";
import { ref, onMounted } from "vue";

// check if student is already enrolled in the class
const has_course = ref(false);
const searched = ref(false);

// TO BE UPDATED: Filter area and Courses Index area can be seperated to two components => ease up the readability
const form = useForm({
    courseID: "",
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                WatchList
            </h2>
        </template>
        <div class="py-9">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <!-- Finder Board -->
                    <h2 class="text-lg font-medium text-gray-900 px-6 py-6">
                        Remove Courses from WatchList
                    </h2>
                    <div class="px-6 text-gray-900">
                        If you'd like to remove a course from your watchlist, simply find it in your list and deregister with a click. <br/>
                        Stay in control of your course selections by managing your watchlist here.
                    </div>

                    <!-- show Watch list courses -->
                    <div
                        class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                    >
                        <WatchList />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
