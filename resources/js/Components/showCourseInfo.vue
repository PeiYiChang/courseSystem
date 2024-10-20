<template>
    <div class="p-6">
        <n-space vertical :size="12">
            <n-data-table :bordered="true" :columns="columns" :data="data" />
        </n-space>
    </div>
</template>

<script>
import { defineComponent, h, ref, onMounted, watch } from "vue";
import { NButton } from "naive-ui";


function createColumns({
    registerCourse,
    deregisterCourse,
    classEnrollment,
}) {
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
                const isEnrolled = classEnrollment.includes(String(row.courseID))

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
    props: {
        data: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        console.log(props.data); // Check what data is received
        const classEnrollment = ref([]);
        const localData = ref([]);

        watch(() => props.data, (newData) => {
            localData.value = newData;
        }, { immediate: true });
        
        onMounted(() => {
            localData.value = [];
            axios.get(route('enrollment.index'), {

            })
                .then(response => {
                    console.log('Response Data:', response.data);
                        response.data.forEach(courseID => {
                        classEnrollment.value.push(String(courseID));
                    });
                    console.log('Success:', response.data);
                    console.log([...classEnrollment.value]);

                })
                .catch(error => {
                    console.error('Error:', error.response.data);
                });
            
                window.addEventListener('beforeunload', () => {
                localData.value = [];
            });
                
        })
        const registerCourse = async (row) => {
    try {
        await axios.post(route("enrollment.store"), {
            courseID: row.courseID,
        });
        classEnrollment.value.push(String(row.courseID)); // Add directly to the array
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
            classEnrollment.value.splice(index, 1); // Remove from the array
        }
    } catch (error) {
        console.error("Error while deregistering course:", error);
    }
};


        const columns = createColumns({
            registerCourse,
            deregisterCourse,
            classEnrollment: classEnrollment.value
        });
        return {
            columns,
            classEnrollment,
            localData,
        };
    }
});
</script>