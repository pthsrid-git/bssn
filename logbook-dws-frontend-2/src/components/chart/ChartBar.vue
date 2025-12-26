<template>
  <div :id="id"></div>
</template>

<script setup lang="ts">
import ApexCharts from 'apexcharts'
import { v4 as uuidv4 } from 'uuid'
import { onMounted, onUnmounted, type PropType } from 'vue'

import type { BarChartData } from '@/models'

const id = 'ChartBar-' + uuidv4()

const props = defineProps({
  data: {
    type: Object as PropType<BarChartData>,
    required: true
  },
  height: {
    type: Number,
    required: true
  },
  borderRadius: {
    type: Number,
    default: 8
  },
  legend: {
    type: Boolean,
    default: false
  },
  legendPosition: {
    type: String,
    default: 'top'
  },
  legendOffsetY: {
    type: Number,
    default: 0
  },
  ySuffix: {
    type: String,
    default: ''
  },
  yMax: {
    type: Number,
    default: undefined
  }
})

const options = {
  series: props.data.series,
  colors: props.data.colors,
  chart: {
    type: 'bar',
    width: '100%',
    height: props.height + 'px',
    fontFamily: 'Inter, sans-serif',
    toolbar: {
      show: false
    }
  },
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: '70%',
      borderRadiusApplication: 'end',
      borderRadius: props.borderRadius
    }
  },
  tooltip: {
    shared: true,
    intersect: false,
    style: {
      fontFamily: 'Inter, sans-serif'
    }
  },
  states: {
    hover: {
      filter: {
        type: 'darken',
        value: 1
      }
    }
  },
  stroke: {
    show: true,
    width: 0,
    colors: ['transparent']
  },
  grid: {
    show: false,
    strokeDashArray: 4,
    padding: {
      left: 2,
      right: 2,
      top: -14
    }
  },
  dataLabels: {
    enabled: false
  },
  legend: {
    show: props.legend,
    position: props.legendPosition,
    fontFamily: 'Inter, sans-serif',
    offsetY: props.legendOffsetY
  },
  xaxis: {
    floating: false,
    labels: {
      show: true,
      style: {
        fontFamily: 'Inter, sans-serif',
        cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
      }
    },
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false
    }
  },
  yaxis: {
    show: true,
    max: props.yMax,
    labels: {
      show: true,
      formatter: function (val: number) {
        return val + props.ySuffix
      }
    },
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false
    }
  },
  fill: {
    opacity: 1
  }
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
