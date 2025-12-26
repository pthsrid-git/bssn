<template>
  <div :id="id"></div>
</template>

<script setup lang="ts">
import ApexCharts from 'apexcharts'
import { v4 as uuidv4 } from 'uuid'
import { onMounted, onUnmounted, type PropType } from 'vue'

import type { PieChartData } from '@/models'

const id = 'ChartPie-' + uuidv4()

const props = defineProps({
  data: {
    type: Object as PropType<PieChartData>,
    required: true
  },
  height: {
    type: Number,
    required: true
  },
  legend: {
    type: Boolean,
    default: false
  },
  legendPosition: {
    type: String,
    default: 'right'
  },
  legendOffsetY: {
    type: Number,
    default: -20
  }
})

const options = {
  labels: props.data.labels,
  series: props.data.values,
  colors: props.data.colors,
  chart: {
    type: 'pie',
    width: '90%',
    height: props.height + 'px',
    fontFamily: 'Inter, sans-serif',
    toolbar: {
      show: false
    }
  },
  plotOptions: {
    pie: {
      dataLabels: {
        offset: -16
      }
    }
  },
  legend: {
    show: props.legend,
    position: props.legendPosition,
    fontFamily: 'Inter, sans-serif',
    offsetY: props.legendOffsetY
  },
  responsive: [
    {
      breakpoint: 768,
      options: {
        chart: {
          width: '100%',
          height: props.height + 64 + 'px'
        },
        legend: {
          position: 'bottom',
          offsetY: 0
        }
      }
    }
  ]
}

let chart: ApexCharts

onMounted(() => {
  if (document.getElementById(id) && typeof ApexCharts !== 'undefined') {
    chart = new ApexCharts(document.getElementById(id), options)
    chart.render()
  }
})

onUnmounted(() => {
  chart.destroy()
})

const printChart = async (title: string = '') => {
  if (!chart) return

  const printWindow = window.open('', '_blank')
  if (!printWindow) return

  try {
    const result = await chart.dataURI()

    if (!('imgURI' in result)) {
      console.error('Chart dataURI did not return imgURI')
      printWindow.close()
      return
    }

    printWindow.document.write(`
      <html>
        <head>
          <title>${title}</title>
          <style>
            body {
              margin: 0;
              display: flex;
              flex-direction: column;
              justify-content: center;
              align-items: center;
              min-height: 100vh;
              padding: 20px;
              box-sizing: border-box;
            }
            h1 {
              margin: 0 0 20px 0;
              font-family: Inter, sans-serif;
              font-size: 14px;
              font-weight: 500;
              color: #333;
              text-align: center;
            }
            img {
              max-width: 100%;
              height: auto;
            }
            @media print {
              body { 
                margin: 0;
                padding: 20px;
              }
              h1 { page-break-after: avoid; }
            }
          </style>
        </head>
        <body>
          <h1>${title}</h1>
          <img src="${result.imgURI}" />
        </body>
      </html>
    `)

    printWindow.document.close()
    printWindow.focus()
    printWindow.onload = () => {
      printWindow.print()
      printWindow.close()
    }
  } catch (err) {
    console.error('Error printing chart:', err)
    printWindow.close()
  }
}

defineExpose({
  printChart
})
</script>
