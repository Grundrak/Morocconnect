<template>
  <div class="chart-container">
    <Bar v-if="chartData" :data="chartData" :options="chartOptions" />
    <p v-else>Loading data...</p>
  </div>
</template>

<script>
import { defineComponent, computed } from 'vue'
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default defineComponent({
  name: 'PostActivityChart',
  components: { Bar },
  props: {
    data: {
      type: Array,
      required: true
    }
  },
  setup(props) {
    const chartData = computed(() => {
      if (!props.data || props.data.length === 0) {
        return null;
      }
      return {
        labels: props.data.map(item => item.date),
        datasets: [
          {
            label: 'New Posts',
            backgroundColor: '#38c172',
            data: props.data.map(item => item.count)
          }
        ]
      }
    })

    const chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Number of New Posts'
          }
        },
        x: {
          title: {
            display: true,
            text: 'Date'
          }
        }
      }
    }

    return { chartData, chartOptions }
  }
})
</script>

<style scoped>
.chart-container {
  height: 300px;
}
</style>