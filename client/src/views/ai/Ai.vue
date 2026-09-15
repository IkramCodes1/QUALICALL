<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const message = ref('')

// 🎯 Appel à ton API FastAPI
onMounted(async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8080/')
    message.value = res.data.message
  } catch (err) {
    console.error('Erreur API:', err)
  }
})
</script>

<template>
  <div class="p-4">
    <h1 class="text-xl font-bold">Message depuis FastAPI :</h1>
    <p>{{ message }}</p>
  </div>
</template>

