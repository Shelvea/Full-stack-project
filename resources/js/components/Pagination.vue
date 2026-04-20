<template>
  <nav v-if="links.length > 3" class="mt-4">
    <ul class="pagination justify-content-end">

      <li
        v-for="(link, index) in filteredLinks"
        :key="index" class="page-item"
        :class="{
          active: link.active,
          disabled: !link.url
        }"
      >
        <button
          class="page-link"
          v-html="link.label"
          @click="changePage(link.url)"
          :disabled="!link.url"
        ></button>
      </li>

    </ul>
  </nav>
</template>

<script setup>
import { computed } from 'vue'
//bootstrap 5 pagination
const props = defineProps({
  links: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['page-changed'])

/*
 Remove the first and last items if needed
 (optional improvement — keeps clean UI)
*/
const filteredLinks = computed(() => {
  return props.links.filter( link => {
    return link.label !== '&laquo; Previous' &&
            link.label !== 'Next &raquo;'

  })
})

function changePage(url) {
  if (url) {
    emit('page-changed', url)
  }
}
</script>