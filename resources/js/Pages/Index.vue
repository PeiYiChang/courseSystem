<template>



    <div>
        <h1>Filtered Courses</h1>

        <!-- Table to display courses -->
        <table class="min-w-full border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="border border-gray-200 p-2">Course ID</th>
                    <th class="border border-gray-200 p-2">Course Title</th>
                    <th class="border border-gray-200 p-2">Instructor</th>
                    <th class="border border-gray-200 p-2">Day</th>
                    <th class="border border-gray-200 p-2">Period</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="course in courses" :key="course.id">
                    <td class="border border-gray-200 p-2">{{ course.courseID }}</td>
                    <td class="border border-gray-200 p-2">{{ course.courseTitle }}</td>
                    <td class="border border-gray-200 p-2">{{ course.instructor }}</td>
                    <td class="border border-gray-200 p-2">{{ course.day }}</td>
                    <td class="border border-gray-200 p-2">{{ course.period }}</td>
                </tr>
                <tr v-if="courses.length === 0">
                    <td colspan="5" class="border border-gray-200 p-2 text-center">No courses found.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        courses: Array, // Expect an array of courses passed from the controller
    },
};
</script>

<style scoped>
/* Add some basic styles for the table */
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 0.5rem;
    text-align: left;
}
</style>
