<template>
    <div class="p-6">
        <n-space vertical :size="12">
            <n-data-table :bordered="true" :columns="columns" :data="data" :pagination="pagination" />
        </n-space>
    </div>
</template>

<script>
import { defineComponent, h, ref, onMounted } from "vue";
import { NButton, NTag, useMessage } from "naive-ui";


function createColumns({
    addToWatchList,
    removeFromWatchList,
    watchList
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
                const isInWatchList = watchList.has(row.courseID)
                return h(
                    NButton,
                    {
                        type: isInWatchList ? "error" : "primary",
                        size: "small",
                        onClick: () => {
                            if(isInWatchList) {
                                removeFromWatchList(row);
                            }else{
                                addToWatchList(row);
                            }
                        }
                    },
                    { default: () => (isInWatchList ? "Remove" : "Add") }
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
        const watchList = ref(new Set());
        console.log(watchList.value); 
        onMounted(() => {
            axios.get(route('watchlist.index'), {

            })
                .then(response => {
                    response.data.forEach(course => {
                        watchList.value.add(course.courseID);
                    });
                    console.log('Success:', response.data);
                    console.log([...watchList.value]); 
                })
                .catch(error => {
                    console.error('Error:', error.response.data);
                });
        })
        const addToWatchList = (row) => {
            
                console.log(`Adding course ${row.courseID} to watchlist.`);
                axios.post(route('watchlist.store'), {
                    courseID: row.courseID
                })
                    .then(response => {
                        watchList.value.add(row.courseID); 
                        //console.log('Success:', response.data);
                    })
                    .catch(error => {
                        console.error('Error:', error.response.data);
                    });
            
        }

        const removeFromWatchList = (row) => {
            
                // remove it from the watchlist table
                axios.post(route('watchlist.remove'),{
                    courseID: row.courseID
                })
                .then(response => {
                    // tableData.value = tableData.value.filter(course => course.courseID !== row.courseID);
                    watchList.value.delete(row.courseID);
                    //console.log('Success:', response.data);
                })
                .catch(error => {
                    console.error('Error:', error.response.data);
                });
                console.log(`remove ${row.courseID} from my watchlist`);
            

        }

        
        const columns = createColumns({
            addToWatchList,
            removeFromWatchList,
            watchList: watchList.value
        });
        const pagination = {
            pageSize: 1,
        };

        return {
            columns,
            pagination,
            watchList,
        };
    }
});
</script>