<template>
  <n-scrollbar x-scrollable>
  <n-table :single-line="false" size="medium" striped="true">
    <thead>
      <tr>
        <th></th>
        <th v-for="period in periods" :key="period">{{ period }}</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(day, index) in days" :key="index">
        <th>{{ day.name }}</th>
        <td v-for="period in periods" :key="period">
          <!-- Render course information for the given day and period -->
          <span v-if="timetable[day.key] && timetable[day.key][period]">
            {{ timetable[day.key][period] }}
          </span>
        </td>
      </tr>
    </tbody>
  </n-table>
</n-scrollbar>
</template>

<script>
import { onMounted, ref } from 'vue';
import { NScrollbar } from 'naive-ui';
import axios from 'axios';

export default {
  setup() {
    const classEnrollment = ref([]);
    const timetable = ref({});
    const days = [
      { name: 'Mon', key: 1 },
      { name: 'Tue', key: 2 },
      { name: 'Wed', key: 3 },
      { name: 'Thu', key: 4 },
      { name: 'Fri', key: 5 },
      { name: 'Sat', key: 6 },
      { name: 'Sun', key: 7 },
    ];
    const periods = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];

    // Helper to process the enrollment data and build the timetable
    const processEnrollmentData = (data) => {
      const newTimetable = {};

      data.forEach((course) => {
        const { day, period, courseTitle } = course;

        // Initialize if the day does not exist in the timetable
        if (!newTimetable[day]) {
          newTimetable[day] = {};
        }

        // Assign the course title to the corresponding day and period
        newTimetable[day][period] = courseTitle;
      });

      timetable.value = newTimetable;
    };

    onMounted(() => {
      axios
        .get(route('enrollment.all'))
        .then((response) => {
          classEnrollment.value = response.data;
          console.log(classEnrollment.value);

          // Process the fetched data into the timetable
          processEnrollmentData(response.data);
        })
        .catch((error) => {
          console.error('Error:', error.response.data);
        });
    });

    return {
      classEnrollment,
      timetable,
      days,
      periods,
    };
  },
};
</script>
