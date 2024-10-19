<template>
    <div class="p-6">
        <n-space vertical :size="12">
            <n-data-table :bordered="true" :columns="columns" :data="tableData" :pagination="pagination" />
        </n-space>
    </div>
</template>


<script>
import { defineComponent, h, onMounted, ref } from "vue";
import { NButton, NTag, useMessage } from "naive-ui";
import axios from "axios";


function createColumns({
    registerCourse
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
                return h(
                    NButton,
                    {   
                        size: "small",
                        onClick: () => registerCourse(row)
                    },
                    { default: () => "register" }
                );
            }
        }
    ];
}


export default defineComponent({
    setup() {

        const tableData = ref([]);
        onMounted(() => {
            // Fetch data from the API
            axios.get(route('watchlist.index'), {

            })
                .then(response => {
                    tableData.value = response.data;
                    console.log('Success:', response.data);
                })
                .catch(error => {
                    console.error('Error:', error.response.data);
                });
        })
        const columns = createColumns({
            registerCourse(row) {
                // register and remove it from the watchlist table
                console.log(`register ${row.courseID} my schdeule`);
            },
        });
        const pagination = {
            pageSize: 6,
        };

        return {
            columns,
            pagination,
            tableData,
        };
    }
});
</script>