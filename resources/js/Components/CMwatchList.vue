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
import { NButton } from "naive-ui";
import axios from "axios";

function createColumns({ registerCourse, deregisterCourse, classEnrollment }) {
    return [
        { title: "ID", key: "courseID" },
        { title: "Title", key: "courseTitle" },
        { title: "Credit", key: "credit" },
        {
            title: "Mandatory",
            key: "mandatory",
            render(row) {
                return row.mandatory === 1 ? 'Required courses' : 'Elective courses';
            }
        },
        { title: "Instructor", key: "instructor" },
        { title: "Department", key: "major" },
        { title: "Grade", key: "grade" },
        { title: "Time", key: "time" },
        {
            title: "Students",
            key: "students",  // Set a new key for this combined column
            render(row) {
                return `${row.currentCapacity} / ${row.maxCapacity}`;
            }
        },
        {
            title: "Enrollment",
            key: "actions",
            render(row) {
                const currentCourseId = String(row.courseID);
                const isEnrolled = classEnrollment.value.includes(currentCourseId);
                
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
                classEnrollment.value = enrollmentResponse.data.map(id => String(id));  // Convert to string for consistency
                console.log('Enrollment Success:', enrollmentResponse.data);
            } catch (error) {
                console.error('Error fetching data:', error.response.data);
            }
        });

        const registerCourse = async (row) => {
            try {
                // 嘗試加選課程
                const enrollResponse = await axios.post(route("enrollment.store"), {
                    courseID: row.courseID,
                });
                alert(enrollResponse.data.message);  // 顯示提示訊息

                if (enrollResponse.data.message === "Course added successfully") {
                    // 成功後更新 UI 狀態
                    classEnrollment.value.push(String(row.courseID));  // 更新已加選課程

                    // 更新對應課程的 currentCapacity
                    const updatedCourse = tableData.value.find(course => course.courseID === row.courseID);
                    if (updatedCourse) {
                        updatedCourse.currentCapacity += 1; // 增加已選容量
                    }
                } else {
                    console.error(enrollResponse.data.message);  // 顯示錯誤訊息
                }
            } catch (error) {
                alert("加選課程失敗，請稍後再試。");
                console.error("Error while registering course:", error);
            }
        };

        const deregisterCourse = async (row) => {
            try {
                // 退選課程
                const deregisterResponse = await axios.post(route("enrollment.remove"), {
                    courseID: row.courseID,
                });
                alert(deregisterResponse.data.message);  // 顯示提示訊息

                if (deregisterResponse.data.message === "Course deregistered successfully") {
                    // 更新 UI 狀態
                    const index = classEnrollment.value.indexOf(String(row.courseID));
                    if (index > -1) {
                        classEnrollment.value.splice(index, 1);  // 移除已選課程
                    }

                    // 更新對應課程的 currentCapacity
                    const updatedCourse = tableData.value.find(course => course.courseID === row.courseID);
                    if (updatedCourse) {
                        updatedCourse.currentCapacity -= 1;  // 減少已選容量
                    }
                } else {
                    console.error(deregisterResponse.data.message);  // 顯示錯誤訊息
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
