<script setup lang="ts">
import { ref} from 'vue';
import type { Ref } from 'vue';
import { useFormsStore } from '@/stores/applicationStore'

const store = useFormsStore()

const firstName = ref('');
const lastName = ref('');
const middleName = ref('');
const birthDate = ref('');
const login = ref('');
const email = ref('');
const message = ref('');
const errors = ref<{ [key: string]: string[] }>({});
const formFields = { firstName, lastName, middleName, birthDate, login, email }

const clearForm = (fields: Record<string, Ref<any>>) => {
  Object.values(fields).forEach(field => field.value = '')
}

const submitForm = async () => {
  errors.value = {};
  message.value = '';

  const payload = {
    firstName: firstName.value,
    lastName: lastName.value,
    middleName: middleName.value,
    birthDate: birthDate.value,
    login: login.value,
    email: email.value,
  };

  try {
    const res = await fetch('/api/form/b', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });

    const data = await res.json();

        if (data.success) {
          clearForm(formFields)
      if (data.application_id && data.classifier) {
        store.setFormResult({ application_id: data.application_id, classifier: data.classifier })
      }
    } else {
      errors.value = data.errors || {};
    }

  } catch (err) {
    message.value = 'Ошибка запроса';
    console.error(err);
  }
};
</script>

<template>
  <form @submit.prevent="submitForm">
     <div class="form-group">
      <label>Имя</label>
      <input v-model="firstName" />
      <span v-if="errors.firstName" class="error">{{ errors.firstName[0] }}</span>
    </div>
     <div class="form-group">
      <label>Фамилия</label>
      <input v-model="lastName" />
      <span v-if="errors.lastName" class="error">{{ errors.lastName[0] }}</span>
    </div>
    <div class="form-group">
      <label>Отчество</label>
      <input v-model="middleName" />
      <span v-if="errors.middleName" class="error">{{ errors.middleName[0] }}</span>
    </div>
     <div class="form-group">
      <label>Дата рождения</label>
      <input v-model="birthDate" type="date" />
      <span v-if="errors.birthDate" class="error">{{ errors.birthDate[0] }}</span>
    </div>
     <div class="form-group">
      <label>Логин</label>
      <input v-model="login" />
      <span v-if="errors.login" class="error">{{ errors.login[0] }}</span>
    </div>
   <div class="form-group">
      <label>Email*</label>
      <input v-model="email" type="email" />
      <span v-if="errors.email" class="error">{{ errors.email[0] }}</span>
    </div>
    <button type="submit">Отправить</button>
    <p>{{ message }}</p>
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
  font-size: 0.9em;
  margin-left: 10px;
}
</style>
