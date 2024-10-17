<template>
  <div class="p-6">
      <n-space vertical :size="12">
          <n-data-table
              :bordered="false"
              :columns="columns"
              :data="data" 
              :pagination="pagination"
          />
      </n-space>
  </div>
</template>

<script>
import { defineComponent, h } from "vue";
import { NDataTable, NSpace, NButton } from "naive-ui";

export default defineComponent({
  props: {
      data: {
          type: Array, // Ensure this is expecting an Array
          required: true,
      },
  },
  setup(props) {
    console.log(props.data); // Check what data is received
    const columns = createColumns();
    const pagination = {
        pageSize: 6,
    };

    return {
        columns,
        pagination,
    };
}
});

function createColumns() {
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
              return h(NButton, { size: "small" }, { default: () => "Add" });
          },
      },
  ];
}
</script>
