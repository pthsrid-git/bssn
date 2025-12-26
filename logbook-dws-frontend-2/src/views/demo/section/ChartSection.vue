<template>
  <div class="flex flex-row flex-wrap items-center justify-center w-full gap-8">
    <div class="xl:w-1/3">
      <ChartBar :data="barChartData" :height="320" :legend="true" />
    </div>
    <div class="xl:w-1/4">
      <ChartPie :data="pieChartData" :height="320" :legend="true" />
    </div>
  </div>
</template>

<script setup lang="ts">
import ChartBar from '@/components/chart/ChartBar.vue'
import ChartPie from '@/components/chart/ChartPie.vue'
import { barChartDataFromJson, pieChartDataFromJson } from '@/models'

const barChartDummy = {
  series: [
    {
      name: 'u',
      data: [
        { x: 'a', y: Math.floor(Math.random() * 100) },
        { x: 'b', y: Math.floor(Math.random() * 100) },
        { x: 'c', y: Math.floor(Math.random() * 100) }
      ]
    },
    {
      name: 'v',
      data: [
        { x: 'a', y: Math.floor(Math.random() * 100) },
        { x: 'b', y: Math.floor(Math.random() * 100) },
        { x: 'c', y: Math.floor(Math.random() * 100) }
      ]
    },
    {
      name: 'w',
      data: [
        { x: 'a', y: Math.floor(Math.random() * 100) },
        { x: 'b', y: Math.floor(Math.random() * 100) },
        { x: 'c', y: Math.floor(Math.random() * 100) }
      ]
    },
    {
      name: 'x',
      data: [
        { x: 'a', y: Math.floor(Math.random() * 100) },
        { x: 'b', y: Math.floor(Math.random() * 100) },
        { x: 'c', y: Math.floor(Math.random() * 100) }
      ]
    },
    {
      name: 'y',
      data: [
        { x: 'a', y: Math.floor(Math.random() * 100) },
        { x: 'b', y: Math.floor(Math.random() * 100) },
        { x: 'c', y: Math.floor(Math.random() * 100) }
      ]
    },
    {
      name: 'z',
      data: [
        { x: 'a', y: Math.floor(Math.random() * 100) },
        { x: 'b', y: Math.floor(Math.random() * 100) },
        { x: 'c', y: Math.floor(Math.random() * 100) }
      ]
    }
  ],
  colors: ['#D0D6DE', '#263053', '#FBC143', '#3FAE19', '#EB303B', '#2563EB']
}
const barChartData = barChartDataFromJson(barChartDummy)
const pieChartDummy = {
  labels: ['u', 'v', 'w', 'x', 'y', 'z'],
  values: [10, 22, 18, 15, 25, 10],
  colors: ['#D0D6DE', '#263053', '#FBC143', '#3FAE19', '#EB303B', '#2563EB']
}
const pieChartData = pieChartDataFromJson(pieChartDummy)
</script>
