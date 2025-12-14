<template>
  <div class="w-full overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right">
      <thead class="text-xs text-gray-500 uppercase bg-neutral-100">
        <slot name="header"></slot>
      </thead>
      <tbody>
        <slot name="body"></slot>
      </tbody>
    </table>
  </div>
</template>
