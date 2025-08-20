<script setup lang="ts">
import { ref } from 'vue';
import type { Ref } from 'vue';
import { useFormsStore } from '@/stores/applicationStore'

const store = useFormsStore()
const name = ref('');
const email = ref('');
const inn = ref('');
const message = ref('');
const errors = ref<{ [key: string]: string[] }>({});
const formFields = { name, email, inn}

const clearForm = (fields: Record<string, Ref<any>>) => {
  Object.values(fields).forEach(field => field.value = '')
}

const submitForm = async () => {
  const payload = { name: name.value, email: email.value, inn: inn.value };

  errors.value = {};
  message.value = '';

  try {
    const res = await fetch('/api/form/a', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });

    const data = await res.json();

    if (data.success) {
    clearForm(formFields)
     message.value = 'Форма успешно отправлена ✅';

      if (data.application_id && data.classifier) {
        store.setFormResult({ application_id: data.application_id, classifier: data.classifier })
      }
    } else {
      message.value = 'Ошибка';
      errors.value = data.errors || {};
    }
  } catch (err) {
    message.value = 'Ошибка запроса';
    console.error(err);
  }
};
</script>

<template>
  <form @submit.prevent="submitForm" class="form">
    <div class="form-group">
      <label>Имя*</label>
      <div class="field">
        <input v-model="name" />
        <span v-if="errors.name" class="error">{{ errors.name[0] }}</span>
      </div>
    </div>

    <div class="form-group">
      <label>Email</label>
      <div class="field">
        <input v-model="email" type="email" />
        <span v-if="errors.email" class="error">{{ errors.email[0] }}</span>
      </div>
    </div>

    <div class="form-group">
      <label>ИНН*</label>
      <div class="field">
        <input v-model="inn" />
        <span v-if="errors.inn" class="error">{{ errors.inn[0] }}</span>
      </div>
    </div>

    <button type="submit">Отправить</button>
       <p v-if="message" class="status">{{ message }}</p>
  
  </form>
</template>

<style scoped>
.form-group {
  display: flex;
  align-items: flex-start;
  margin-bottom: 1rem;
}

.form-group label {
  width: 60px;
  margin-right: 10px;
}

.field {
  flex: 1;
}

.field input {
  width: 100%;
  padding: 5px;
}

.error {
  color: red;
  font-size: 0.875rem;
  margin-top: 3px;
}
</style>
