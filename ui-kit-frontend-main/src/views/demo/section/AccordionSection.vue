<template>
  <div class="flex flex-row flex-wrap items-center justify-center gap-2">
    <AccordionDefault v-model="accordionActive" #default="{ active, toggle }">
      <AccordionItem title="First Item" :index="0" :active="active" @toggle="toggle">
        <div>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur venenatis, risus ac
          bibendum posuere, sapien lorem ultricies risus, vel sodales mi justo sit amet arcu. Mauris
          non pulvinar erat, vitae tristique libero dictum sit amet. Integer vitae orci non sapien
          bibendum porttitor a at est. Donec euismod, sapien nec tincidunt lacinia, lectus orci
          suscipit neque, a dictum nunc urna nec nunc.
        </div>
      </AccordionItem>
      <AccordionItem title="Second Item" :index="1" :active="active" @toggle="toggle">
        <div class="flex flex-col gap-2">
          <div>
            Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
            laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
            architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas
            sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione
            voluptatem sequi nesciunt. Quis autem vel eum iure reprehenderit qui in ea voluptate
            velit esse quam nihil molestiae consequatur?
          </div>
          <div>
            At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium
            voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint
            occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt
            mollitia animi. Et harum quidem rerum facilis est et expedita distinctio. Nam libero
            tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod
            maxime placeat facere possimus.
          </div>
        </div>
      </AccordionItem>
      <AccordionItem title="Third Item" :index="2" :active="active" @toggle="toggle">
        <div>
          Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam,
          nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea
          voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat
          quo voluptas nulla pariatur? Aliquam erat volutpat. Integer sed leo metus. Aenean eu
          bibendum lorem, sed cursus elit. Phasellus dictum, neque non efficitur efficitur, nisi
          magna tincidunt felis, vitae lacinia est lectus et odio.
        </div>
      </AccordionItem>
    </AccordionDefault>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

import AccordionDefault from '@/components/accordion/AccordionDefault.vue'
import AccordionItem from '@/components/accordion/AccordionItem.vue'

const accordionActive = ref<number | null>(null)
</script>
