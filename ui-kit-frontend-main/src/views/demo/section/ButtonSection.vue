<template>
  <div class="flex flex-row flex-wrap items-center justify-center gap-2">
    <div class="flex flex-col gap-y-2">
      <ButtonDefault variant="default" @click="handleActionableButton('default')">
        Default Button
      </ButtonDefault>
      <ButtonDefault
        variant="default"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('default')"
      >
        Default Button
      </ButtonDefault>
      <ButtonOutline variant="default" @click="handleActionableButton('default')">
        Default Button
      </ButtonOutline>
      <ButtonOutline
        variant="default"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('default')"
      >
        Default Button
      </ButtonOutline>
      <ButtonLink variant="default" @click="handleActionableButton('default')">
        Default Button
      </ButtonLink>
      <ButtonLink
        variant="default"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('default')"
      >
        Default Button
      </ButtonLink>
    </div>
    <div class="flex flex-col gap-y-2">
      <ButtonDefault variant="primary" @click="handleActionableButton('primary')">
        Primary Button
      </ButtonDefault>
      <ButtonDefault
        variant="primary"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('primary')"
      >
        Primary Button
      </ButtonDefault>
      <ButtonOutline variant="primary" @click="handleActionableButton('primary')">
        Primary Button
      </ButtonOutline>
      <ButtonOutline
        variant="primary"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('primary')"
      >
        Primary Button
      </ButtonOutline>
      <ButtonLink variant="primary" @click="handleActionableButton('primary')">
        Primary Button
      </ButtonLink>
      <ButtonLink
        variant="primary"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('primary')"
      >
        Primary Button
      </ButtonLink>
    </div>
    <div class="flex flex-col gap-y-2">
      <ButtonDefault variant="warning" @click="handleActionableButton('warning')">
        Warning Button
      </ButtonDefault>
      <ButtonDefault
        variant="warning"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('warning')"
      >
        Warning Button
      </ButtonDefault>
      <ButtonOutline variant="warning" @click="handleActionableButton('warning')">
        Warning Button
      </ButtonOutline>
      <ButtonOutline
        variant="warning"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('warning')"
      >
        Warning Button
      </ButtonOutline>
      <ButtonLink variant="warning" @click="handleActionableButton('warning')">
        Warning Button
      </ButtonLink>
      <ButtonLink
        variant="warning"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('warning')"
      >
        Warning Button
      </ButtonLink>
    </div>
    <div class="flex flex-col gap-y-2">
      <ButtonDefault variant="success" @click="handleActionableButton('success')">
        Success Button
      </ButtonDefault>
      <ButtonDefault
        variant="success"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('success')"
      >
        Success Button
      </ButtonDefault>
      <ButtonOutline variant="success" @click="handleActionableButton('success')">
        Success Button
      </ButtonOutline>
      <ButtonOutline
        variant="success"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('success')"
      >
        Success Button
      </ButtonOutline>
      <ButtonLink variant="success" @click="handleActionableButton('success')">
        Success Button
      </ButtonLink>
      <ButtonLink
        variant="success"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('success')"
      >
        Success Button
      </ButtonLink>
    </div>
    <div class="flex flex-col gap-y-2">
      <ButtonDefault variant="danger" @click="handleActionableButton('danger')">
        Danger Button
      </ButtonDefault>
      <ButtonDefault
        variant="danger"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('danger')"
      >
        Danger Button
      </ButtonDefault>
      <ButtonOutline variant="danger" @click="handleActionableButton('danger')">
        Danger Button
      </ButtonOutline>
      <ButtonOutline
        variant="danger"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('danger')"
      >
        Danger Button
      </ButtonOutline>
      <ButtonLink variant="danger" @click="handleActionableButton('danger')">
        Danger Button
      </ButtonLink>
      <ButtonLink
        variant="danger"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('danger')"
      >
        Danger Button
      </ButtonLink>
    </div>
    <div class="flex flex-col gap-y-2">
      <ButtonDefault variant="info" @click="handleActionableButton('info')">
        Info Button
      </ButtonDefault>
      <ButtonDefault
        variant="info"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('info')"
      >
        Info Button
      </ButtonDefault>
      <ButtonOutline variant="info" @click="handleActionableButton('info')">
        Info Button
      </ButtonOutline>
      <ButtonOutline
        variant="info"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('info')"
      >
        Info Button
      </ButtonOutline>
      <ButtonLink variant="info" @click="handleActionableButton('info')"> Info Button </ButtonLink>
      <ButtonLink
        variant="info"
        :loading="true"
        :disabled="true"
        @click="handleActionableButton('info')"
      >
        Info Button
      </ButtonLink>
    </div>
  </div>
  <div class="flex flex-row flex-wrap items-center justify-center gap-2">
    <ButtonView></ButtonView>
    <ButtonAdd></ButtonAdd>
    <ButtonDownload></ButtonDownload>
    <ButtonSave></ButtonSave>
    <ButtonDelete></ButtonDelete>
    <ButtonEdit></ButtonEdit>
  </div>
  <div class="flex flex-row flex-wrap items-center justify-center gap-2">
    <ButtonView :disabled="true"></ButtonView>
    <ButtonAdd :disabled="true"></ButtonAdd>
    <ButtonDownload :disabled="true"></ButtonDownload>
    <ButtonSave :disabled="true"></ButtonSave>
    <ButtonDelete :disabled="true"></ButtonDelete>
    <ButtonEdit :disabled="true"></ButtonEdit>
  </div>
  <div class="flex flex-row flex-wrap items-center justify-center gap-2">
    <ButtonIcon variant="default" @click="handleActionableButton('default')">
      <font-awesome-icon :icon="['fad', 'shapes']" class="w-5 h-5" />
    </ButtonIcon>
    <ButtonIcon variant="primary" @click="handleActionableButton('parimary')">
      <font-awesome-icon :icon="['fad', 'shapes']" class="w-5 h-5" />
    </ButtonIcon>
    <ButtonIcon variant="warning" @click="handleActionableButton('warning')">
      <font-awesome-icon :icon="['fad', 'shapes']" class="w-5 h-5" />
    </ButtonIcon>
    <ButtonIcon variant="success" @click="handleActionableButton('success')">
      <font-awesome-icon :icon="['fad', 'shapes']" class="w-5 h-5" />
    </ButtonIcon>
    <ButtonIcon variant="danger" @click="handleActionableButton('danger')">
      <font-awesome-icon :icon="['fad', 'shapes']" class="w-5 h-5" />
    </ButtonIcon>
    <ButtonIcon variant="info" @click="handleActionableButton('info')">
      <font-awesome-icon :icon="['fad', 'shapes']" class="w-5 h-5" />
    </ButtonIcon>
  </div>
  <div class="flex flex-row flex-wrap items-center justify-center gap-2">
    <ButtonIcon variant="default" :disabled="true" @click="handleActionableButton('default')">
      <font-awesome-icon :icon="['fad', 'shapes']" class="w-5 h-5" />
    </ButtonIcon>
    <ButtonIcon variant="primary" :disabled="true" @click="handleActionableButton('parimary')">
      <font-awesome-icon :icon="['fad', 'shapes']" class="w-5 h-5" />
    </ButtonIcon>
    <ButtonIcon variant="warning" :disabled="true" @click="handleActionableButton('warning')">
      <font-awesome-icon :icon="['fad', 'shapes']" class="w-5 h-5" />
    </ButtonIcon>
    <ButtonIcon variant="success" :disabled="true" @click="handleActionableButton('success')">
      <font-awesome-icon :icon="['fad', 'shapes']" class="w-5 h-5" />
    </ButtonIcon>
    <ButtonIcon variant="danger" :disabled="true" @click="handleActionableButton('danger')">
      <font-awesome-icon :icon="['fad', 'shapes']" class="w-5 h-5" />
    </ButtonIcon>
    <ButtonIcon variant="info" :disabled="true" @click="handleActionableButton('info')">
      <font-awesome-icon :icon="['fad', 'shapes']" class="w-5 h-5" />
    </ButtonIcon>
  </div>
  <div class="flex justify-center gap-2">
    <ButtonClose @click="handleActionableButton('close')" />
  </div>
  <div class="flex justify-center gap-2">
    <ButtonMenu @click="handleActionableButton('menu')" />
  </div>
  <div class="flex justify-center gap-2">
    <ButtonChat :badge="1" @click="handleActionableButton('chat')" />
  </div>
</template>

<script setup lang="ts">
import ButtonAdd from '@/components/button/ButtonAdd.vue'
import ButtonChat from '@/components/button/ButtonChat.vue'
import ButtonClose from '@/components/button/ButtonClose.vue'
import ButtonDefault from '@/components/button/ButtonDefault.vue'
import ButtonDelete from '@/components/button/ButtonDelete.vue'
import ButtonDownload from '@/components/button/ButtonDownload.vue'
import ButtonEdit from '@/components/button/ButtonEdit.vue'
import ButtonIcon from '@/components/button/ButtonIcon.vue'
import ButtonLink from '@/components/button/ButtonLink.vue'
import ButtonMenu from '@/components/button/ButtonMenu.vue'
import ButtonOutline from '@/components/button/ButtonOutline.vue'
import ButtonSave from '@/components/button/ButtonSave.vue'
import ButtonView from '@/components/button/ButtonView.vue'
import { log } from '@/helpers'

const handleActionableButton = (type: string) => {
  log(`Button ${type} clicked`)
}
</script>
