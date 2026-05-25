<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Upload from '@/views/upload/Upload.vue'

const router = useRouter()

const selected = ref(null)
const calls = ref([])
const drawer = ref(false)

// fetch
async function fetchCalls() {
  const res = await axios.get('http://localhost:8000/api/conversation/get')

  calls.value = res.data.data.map(c => ({
    id: c.id,
    name: c.name || 'Sans nom',
    date: c.call_date,
    service: 'Service',
    duration: formatDuration(c.duration),
    path: c.path
  }))
}

onMounted(fetchCalls)

// helpers
function formatDuration(seconds) {
  if (!seconds) return '00:00'
  const m = Math.floor(seconds / 60)
  const s = Math.floor(seconds % 60)
  return `${m}:${s.toString().padStart(2, '0')}`
}

// navigation
function goToAudio(call) {
  selected.value = call
  router.push({
    name: 'audio',
    params: { id: call.id },
  })
}
</script>

<template>
  <v-container>

    <!-- HEADER -->
    <div class="d-flex justify-space-between align-center mb-4">
      <h2>Appels</h2>

      <v-btn color="#9155FD" @click="drawer = true">
        + {{ $t('upload') }}
      </v-btn>
    </div>

    <!-- DRAWER -->
    <Upload v-model="drawer" @uploaded="fetchCalls" />

    <!-- LIST -->
    <v-card
      v-for="(call, i) in calls"
      :key="i"
      class="call-card"
      :class="{ active: selected?.id === call.id }"
      @click="goToAudio(call)"
    >
      <div class="call-item">

        <div class="avatar">
          <i class="ri-headphone-line"></i>
        </div>

        <div class="content">
          <div class="title">{{ call.name }}</div>
          <div class="subtitle">
            {{ call.date }} • {{ call.service }}
          </div>
        </div>

        <v-chip class="duration-chip">
          {{ call.duration }}
        </v-chip>

      </div>
    </v-card>

  </v-container>
</template>


<style scoped>
.call-card {
  margin-bottom: 10px;
  border-radius: 14px;
  transition: all 0.2s ease;
  cursor: pointer;
  border: 1px solid transparent;
}

.call-card:hover {
  background: #f3eaff;
  transform: translateY(-2px);
  color: rgb(36, 36, 36);
}

.call-card.active {
  border: 1px solid #9155FD;
  background: #ede7f6;
}

.call-item {
  display: flex;
  align-items: center;
  padding: 14px;
}

/* ICON */
.avatar {
  width: 48px;
  height: 48px;
  background: linear-gradient(135deg, #9155FD, #6a82fb);
  color: white;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  margin-right: 12px;
}

/* TEXT */
.content {
  flex: 1;
}

.title {
  font-weight: 600;
  font-size: 14px;
}

.subtitle {
  font-size: 12px;
  color: #777;
}

/* CHIP */
.duration-chip {
  background: #ede7f6;
  color: #9155FD;
  font-weight: 600;
}
</style>
