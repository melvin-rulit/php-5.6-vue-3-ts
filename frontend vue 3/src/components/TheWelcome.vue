<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

interface Project {
  id: number | string
  name: string
}

const messages = ref<Project[]>([])
let ws: WebSocket | null = null

onMounted(() => {
  ws = new WebSocket("ws://localhost:8000/ws")

  ws.onopen = () => console.log("WebSocket connected")
  ws.onerror = (e) => console.error("WebSocket error", e)
  ws.onmessage = (event) => {
    try {
      const data = JSON.parse(event.data)
      if (Array.isArray(data)) {
        messages.value.push(...data)
      } else {
        messages.value.push(data)
      }
    } catch (e) {
      console.error('Invalid JSON', e)
    }
  }
})

onUnmounted(() => {
  if (ws) ws.close()
})
</script>

<template>
  <section class="projects-container">
    <h2>Полученные проекты</h2>

    <p v-if="messages.length === 0" class="empty-message">
      Список проектов пуст.
    </p>

    <ul v-else class="projects-list">
      <li v-for="project in messages" :key="project.id" class="project-item">
        <span class="project-id">{{ project.id }}</span>
        &mdash;
        <span class="project-name">{{ project.name }}</span>
      </li>
    </ul>
  </section>
</template>

<style scoped>
.projects-container {
  max-width: 600px;
  margin: 20px auto;
  padding: 15px 20px;
  background: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgb(0 0 0 / 0.1);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

h2 {
  text-align: center;
  color: #2c3e50;
  margin-bottom: 1rem;
}

.empty-message {
  color: #888;
  font-style: italic;
  text-align: center;
  padding: 1rem 0;
}

.projects-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.project-item {
  background: white;
  padding: 12px 15px;
  margin-bottom: 10px;
  border-radius: 6px;
  border: 1px solid #ddd;
  transition: background-color 0.25s ease;
  display: flex;
  gap: 8px;
  font-size: 1.1rem;
  cursor: default;
}

.project-item:hover {
  background-color: #e6f0ff;
}

.project-id {
  font-weight: 600;
  color: #1a73e8;
  min-width: 30px;
}

.project-name {
  color: #333;
}
</style>
