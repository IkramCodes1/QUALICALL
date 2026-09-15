<script setup>
import axios from 'axios'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

// props
const props = defineProps({
  modelValue: Boolean
})

// emits
const emit = defineEmits(['update:modelValue', 'uploaded'])

// state
const file = ref(null)
const fileInput = ref(null)
const date = ref('')
const time = ref('')
const loading = ref(false)
const snackbar = ref(false)

// actions
function openFile() {
  fileInput.value.click()
}

function handleFile(e) {
  file.value = e.target.files[0]
}

function handleDrop(e) {
  file.value = e.dataTransfer.files[0]
}

function resetForm() {
  file.value = null
  date.value = ''
  time.value = ''
}

function closeDrawer() {
  resetForm()
  emit('update:modelValue', false)
}

async function upload() {
  if (!file.value) {
    alert('Choisir fichier')
    return
  }

  loading.value = true

  const formData = new FormData()
  formData.append('audio', file.value)
  formData.append('name', file.value.name)
  formData.append('duration', 120)
  formData.append('societe_id', 1)
  formData.append('dialogues', JSON.stringify([]))

  if (date.value && time.value) {
    formData.append('call_date', `${date.value} ${time.value}`)
  }

  try {
    await axios.post(
      'http://127.0.0.1:8000/api/upload/audio',
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }
    )

    snackbar.value = true
    emit('uploaded') // refresh list
    closeDrawer()

  } catch (err) {
    console.error('🔥 VALIDATION ERROR:', err.response?.data)

    console.log(err)
    console.log(err.response)
    console.log(err.response?.data)

  } finally {
    loading.value = false
  }
}
</script>

<template>
  <v-navigation-drawer :model-value="modelValue" @update:modelValue="emit('update:modelValue', $event)" location="right"
    width="400" temporary>

    <v-progress-linear v-if="loading" indeterminate color="#9155FD" />

    <div class="pa-4">

      <div class="d-flex justify-space-between align-center mb-4">
        <h3>{{ $t('upAudio') }}</h3>
        <v-btn icon @click="closeDrawer">✕</v-btn>
      </div>

      <div class="drop-zone" @dragover.prevent @drop.prevent="handleDrop" @click="openFile">
        <div class="text-center">
          <i class="ri-music-2-line icon"></i>
          <p class="mt-2">{{ $t('clique') }}</p>
          <small>MP3, WAV</small>
          <input type="file" hidden ref="fileInput" @change="handleFile" />
        </div>
      </div>

      <div v-if="file" class="mt-3 file-box">
        📄 {{ file.name }}
      </div>

      <v-row class="mt-3">
        <v-col cols="6">
          <v-text-field v-model="date" type="date" label="Date" />
        </v-col>
        <v-col cols="6">
          <v-text-field v-model="time" type="time" label="Heure" />
        </v-col>
      </v-row>

      <div class="d-flex justify-end mt-4">
        <v-btn variant="text" @click="resetForm">
          {{ $t('Cancel') }}
        </v-btn>

        <v-btn color="#9155FD" class="ml-2" :loading="loading" @click="upload">
          {{ $t('Save') }}
        </v-btn>
      </div>

    </div>
  </v-navigation-drawer>

  <v-snackbar v-model="snackbar" color="green">
    {{ $t('uploadSuccess') }} ✅
  </v-snackbar>
</template>

<style scoped>
.drop-zone {
  border: 2px dashed #ccc;
  border-radius: 12px;
  padding: 25px;
  cursor: pointer;
  transition: 0.3s;
}

.drop-zone:hover {
  border-color: #9155FD;
  background: #f4f0ff;
  color: rgb(29, 29, 29);
}

.icon {
  font-size: 40px;
  color: #9155FD;
}

.file-box {
  background: #a06de4;
  padding: 10px;
  border-radius: 8px;
}
</style>
