<template>
  <div class="p-6">
      <n-space vertical :size="12">
          <n-data-table
              :bordered="true"
              :columns="columns"
              :data="data" 
              :pagination="pagination"
          />
      </n-space>
  </div>
</template>

 <script>
 import { defineComponent, h } from "vue";
 import { NButton, NTag, useMessage } from "naive-ui";
 
 
 function createColumns({
   addToWatchList
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
             onClick: () => addToWatchList(row)
           },
           { default: () => "Add" }
         );
       }
     }
   ];
 }
 
 
 export default defineComponent({
     props:{
         data:{
             type: Array,
             required: true, 
         },
     },
     setup(props) {
         
         console.log(props.data); // Check what data is received
     const columns = createColumns({
         addToWatchList(row) {
             console.log(`Adding course ${row.courseID} to watchlist.`);
         },
     });
     const pagination = {
         pageSize: 6,
     };
 
     return {
         columns,
         pagination,
     };
   }
 });
 </script>