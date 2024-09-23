<template>
    <div class="chart-container">
      <Line v-if="chartData" :data="chartData" :options="chartOptions" />
      <p v-else>Loading data...</p>
    </div>
  </template>
  
  <script>
  import { defineComponent, computed, watch } from 'vue'
  import { Line } from 'vue-chartjs'
  import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, LinearScale, PointElement, CategoryScale } from 'chart.js'
  
  ChartJS.register(Title, Tooltip, Legend, LineElement, LinearScale, PointElement, CategoryScale)
  
  export default defineComponent({
    name: 'UserGrowthChart',
    components: { Line },
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
              label: 'New Users',
              backgroundColor: '#3490dc',
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
              text: 'Number of New Users'
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