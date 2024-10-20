<template>
    <div class="p-6">
        <n-space vertical :size="12">
            <n-data-table 
                :bordered="true" 
                :columns="columns" 
                :data="tableData" 
                :pagination="pagination" 
            />
        </n-space>
    </div>
</template>

<script>
import { defineComponent, h, onMounted, ref } from "vue";
import { NButton, NTag, useMessage } from "naive-ui";
import axios from "axios";

function createColumns({ registerCourse, deregisterCourse, classEnrollment }) {
    return [
        { title: "ID", key: "courseID" },
        { title: "Title", key: "courseTitle" },
        { title: "Credit", key: "credit" },
        { title: "Mandatory", key: "mandatory" },
        { title: "Instructor", key: "instructor" },
        { title: "Department", key: "major" },
        { title: "Grade", key: "grade" },
        { title: "Time", key: "time" },
        {
            title: "Enrollment",
            key: "actions",
            render(row) {
                // Convert both to strings for comparison
                const currentCourseId = String(row.courseID);
                const isEnrolled = classEnrollment.value.some(id => String(id) === currentCourseId);
                
                return h(
                    NButton,
                    {   
                        type: isEnrolled ? "error" : "primary",
                        size: "small",
                        onClick: () => {
                            if (isEnrolled) {
                                deregisterCourse(row);
                            } else {
                                registerCourse(row);
                            }
                        }
                    },
                    { default: () => (isEnrolled ? "de-register" : "register") }
                );
            }
        }
    ];
}

export default defineComponent({
    setup() {
        const tableData = ref([]);
        const classEnrollment = ref([]);

        onMounted(async () => {
            try {
                const watchlistResponse = await axios.get(route('watchlist.index'));
                tableData.value = watchlistResponse.data;
                console.log('Watchlist Success:', watchlistResponse.data);
                
                const enrollmentResponse = await axios.get(route('enrollment.index'));
                classEnrollment.value = enrollmentResponse.data;
                console.log('Enrollment Success:', enrollmentResponse.data);
            } catch (error) {
                console.error('Error fetching data:', error.response.data);
            }
        });

        const registerCourse = async (row) => {
            try {
                await axios.post(route("enrollment.store"), {
                    courseID: row.courseID,
                });
                classEnrollment.value.push(String(row.courseID));
            } catch (error) {
                console.error("Error while registering course:", error);
            }
        };

        const deregisterCourse = async (row) => {
            try {
                await axios.post(route("enrollment.remove"), {
                    courseID: row.courseID,
                });
                const index = classEnrollment.value.indexOf(String(row.courseID));
                if (index > -1) {
                    classEnrollment.value.splice(index, 1);
                }
            } catch (error) {
                console.error("Error while deregistering course:", error);
            }
        };

        const columns = createColumns({
            registerCourse,
            deregisterCourse,
            classEnrollment
        });

        const pagination = {
            pageSize: 6,
        };

        return {
            columns,
            pagination,
            tableData,
            classEnrollment,
        };
    }
});
</script>