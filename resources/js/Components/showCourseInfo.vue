<template>
    <div class="p-6">
        <n-space vertical :size="12">
            <n-data-table :bordered="true" :columns="columns" :data="tableData" />
        </n-space>
    </div>
</template>

<script>
import { defineComponent, h, ref, onMounted } from "vue";
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
                return row.mandatory === 1 ? "Required courses" : "Elective courses";
            },
        },
        { title: "Instructor", key: "instructor" },
        { title: "Department", key: "major" },
        { title: "Grade", key: "grade" },
        { title: "Time", key: "time" },
        {
            title: "Students",
            key: "students",
            render(row) {
                return `${row.currentCapacity} / ${row.maxCapacity}`;
            },
        },
        {
            title: "Enrollment",
            key: "actions",
            render(row) {
                const isEnrolled = classEnrollment.includes(String(row.courseID));
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
                        },
                    },
                    { default: () => (isEnrolled ? "de-register" : "register") }
                );
            },
        },
    ];
}

export default defineComponent({
    props: {
        data: {
            type: Array,
            required: false,
            default: () => [], // 預設為空陣列
        },
    },
    setup(props) {
        // 初始化課程資料
        const tableData = ref(props.data || []);
        const classEnrollment = ref([]);

        // 獲取已選課程的資訊
        onMounted(() => {
            axios
                .get(route("enrollment.index"))
                .then((response) => {
                    response.data.forEach((courseID) => {
                        classEnrollment.value.push(String(courseID));
                    });
                })
                .catch((error) => {
                    console.error("Error:", error.response.data);
                });
        });

        // 加選課程
        const registerCourse = async (row) => {
            try {
                const enrollResponse = await axios.post(route("enrollment.store"), {
                    courseID: row.courseID,
                });
                alert(enrollResponse.data.message);

                if (enrollResponse.data.message === "Course added successfully") {
                    classEnrollment.value.push(String(row.courseID));
                    const updatedCourse = tableData.value.find((course) => course.courseID === row.courseID);
                    if (updatedCourse) {
                        updatedCourse.currentCapacity += 1;
                    }
                } else {
                    console.error(enrollResponse.data.message);
                }
            } catch (error) {
                console.error("Error while registering course:", error);
            }
        };

        // 退選課程
        const deregisterCourse = async (row) => {
            try {
                const deregisterResponse = await axios.post(route("enrollment.remove"), {
                    courseID: row.courseID,
                });
                alert(deregisterResponse.data.message);

                if (deregisterResponse.data.message === "Course deregistered successfully") {
                    const index = classEnrollment.value.indexOf(String(row.courseID));
                    if (index > -1) {
                        classEnrollment.value.splice(index, 1);
                    }
                    const updatedCourse = tableData.value.find((course) => course.courseID === row.courseID);
                    if (updatedCourse) {
                        updatedCourse.currentCapacity -= 1;
                    }
                } else {
                    console.error(deregisterResponse.data.message);
                }
            } catch (error) {
                console.error("Error while deregistering course:", error);
            }
        };

        const columns = createColumns({
            registerCourse,
            deregisterCourse,
            classEnrollment: classEnrollment.value,
        });

        return {
            tableData, // 綁定到模板
            columns,
            classEnrollment,
        };
    },
});
</script>
