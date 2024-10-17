<template>
    <!-- <n-p>你选中了 {{ checkedRowKeys.length }} 行。</n-p> -->
    <div class="p-6">
    <n-data-table
      v-model:checked-row-keys="checkedRowKeys"
      :columns="columns"
      :data="data"
      :pagination="pagination"
    />
    </div>
  </template>
  
  <script>
  import { defineComponent, ref } from "vue";
  import { repeat } from "seemly";
  import { NDataTable } from "naive-ui";
  
  export default defineComponent({
    components: {
      NDataTable // Ensure the component is registered
    },
    setup() {
      const checkedRowKeysRef = ref([]);
    //   const data = ref([]);
      const data = repeat(46, void 0).map((_, index) => ({
        courseID: `Edward King ${index}`,
        courseTitle: 32,
        credit:3,
        mandatory: 'yes',
        major: `Compuer Science and engineering`,
        grade: 4,
        time: 'Monday 06-09, Tuesday 08-09',
        key: index
      }));
      const columns = [
        {
          type: "selection",
          options: ["all", "none"],
        },
        {
          title: "Course ID",
          key: "courseID",
        },
        {
          title: "Title",
          key: "courseTitle",
        },
        {
          title: "Credit",
          key: "credit",
        },
        {
          title: "Mandatory",
          key: "mandatory",
        },
        {
          title: "Major",
          key: "major",
        },
        {
          title: "Grade",
          key: "grade",
        },
        {
          title: "Time",
          key: "time",
        },
        // Time equals Day + period
      ];
  
      return {
        checkedRowKeys: checkedRowKeysRef,
        data,
        pagination: {
          pageSize: 6,
        },
        columns,
      };
    },
  });
  </script>
  
  <style scoped>
  </style>
  