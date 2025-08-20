<script setup lang="ts">
import { ref } from 'vue';
import FormA from './components/FormaA.vue';
import FormB from './components/FormaB.vue';

const activeForm = ref<'A' | 'B'>('A');

const showFormA = () => activeForm.value = 'A';
const showFormB = () => activeForm.value = 'B';
</script>

<template>
  <div>
    <div class="tabs">
      <button @click="showFormA" :class="{ active: activeForm === 'A' }">Форма А</button>
      <button @click="showFormB" :class="{ active: activeForm === 'B' }">Форма Б</button>
    </div>

    <div class="forms">
      <FormA v-if="activeForm === 'A'" />
      <FormB v-if="activeForm === 'B'" />
    </div>
  </div>
</template>

<style scoped>
.tabs button {
  margin-right: 1rem;
  padding: 0.5rem 1rem;
  cursor: pointer;
}

.tabs button.active {
  font-weight: bold;
  background-color: #eee;
}

.forms {
  margin-top: 1rem;
}
</style>
