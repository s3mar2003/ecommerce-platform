<template>
  <div class="w-full h-64">
    <canvas ref="chartRef"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
  data: {
    type: Object,
    required: true
  },
  options: {
    type: Object,
    default: () => ({})
  }
});

const chartRef = ref(null);
let chart = null;

onMounted(() => {
  if (chartRef.value) {
    renderChart();
  }
});

watch(() => props.data, () => {
  if (chart) {
    chart.destroy();
  }
  renderChart();
});

const renderChart = () => {
  if (chartRef.value) {
    chart = new Chart(chartRef.value, {
      type: 'bar',
      data: props.data,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0, 0, 0, 0.1)'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        },
        ...props.options
      }
    });
  }
};
</script>