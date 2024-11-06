<script>
import { defineComponent, h, onMounted } from "vue";
import { NButton, NTag, useMessage } from "naive-ui";
import axios from "axios";


function createColumns({
    removeFromWatchList
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
            title: "Watch List",
            key: "actions",
            render(row) {
                return h(
                    NButton,
                    {
                        size: "small",
                        onClick: () => removeFromWatchList(row)
                    },
                    { default: () => "Remove" }
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
            axios.get(route('/watchlist.index'), {

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
            removeFromWatchList(row) {
                console.log(`Remove ${row.courseID} from the watchlist.`);
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