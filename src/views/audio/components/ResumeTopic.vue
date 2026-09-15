<template>
  <v-card class="resume-topic-card">

    <div v-for="(topic, i) in topics" :key="i" class="topic-block">

      <!-- TOPIC HEADER -->
      <div class="topic-header" :style="{ backgroundColor: topic.color }" @click="emit('seek', topic.start_time)">
        <div class="topic-name">
          {{ topic.name }}
        </div>

        <div class="topic-time">
          {{ formatTime(topic.start_time) }}
          -
          {{ formatTime(topic.end_time) }}
        </div>
      </div>

      <!-- RESUME -->
      <div class="resume-content">

        {{
          resumeTopics[i]?.text ||
          'Aucun résumé disponible'
        }}

      </div>

    </div>

    <div v-if="topics.length === 0">
      Aucun topic disponible
    </div>

  </v-card>
</template>

<script setup>

const emit = defineEmits(['seek'])

defineProps({
  topics: {
    type: Array,
    default: () => []
  },

  resumeTopics: {
    type: Array,
    default: () => []
  }
})

function formatTime(seconds) {

  if (!seconds) return '0:00'

  const m = Math.floor(seconds / 60)
  const s = Math.floor(seconds % 60)

  return `${m}:${s.toString().padStart(2, '0')}`
}

</script>

<style scoped>

/* ====================== */
/* COMMON */
/* ====================== */

.resume-topic-card {

  height: 400px;

  overflow-y: auto;

  padding: 12px;

  border-radius: 20px;

  transition: .3s ease;
}

.topic-block {

  margin-bottom: 18px;

  border-radius: 16px;

  overflow: hidden;

  transition: .25s ease;
}

.topic-header {

  padding: 14px;

  display: flex;

  justify-content: space-between;

  align-items: center;

  cursor: pointer;

  transition: .25s ease;
}

.topic-header:hover {

  filter: brightness(1.05);
}

.topic-name {

  font-weight: bold;

  font-size: 15px;
}

.topic-time {

  font-size: 13px;

  opacity: 0.9;
}

.resume-content {

  padding: 16px;

  line-height: 1.8;
}

/* ====================== */
/* DARK MODE */
/* ====================== */

.v-theme--dark .resume-topic-card {

  background:
    linear-gradient(
      135deg,
      #1e1e2f,
      #2b2b45
    );

  color: white;
}

.v-theme--dark .topic-block {

  background:
    rgba(255,255,255,.04);

  border:
    1px solid rgba(255,255,255,.05);

  box-shadow:
    0 4px 16px rgba(0,0,0,.25);
}

.v-theme--dark .resume-content {

  color: #ddd;
}

/* ====================== */
/* LIGHT MODE */
/* ====================== */

.v-theme--light .resume-topic-card {

  background:
    linear-gradient(
      135deg,
      #ffffff,
      #f5f7ff
    );

  color: #2b2b45;

  box-shadow:
    0 8px 24px rgba(0,0,0,.05);
}

.v-theme--light .topic-block {

  background:
    white;

  border:
    1px solid rgba(0,0,0,.06);

  box-shadow:
    0 4px 14px rgba(0,0,0,.05);
}

.v-theme--light .resume-content {

  color:
    rgba(0,0,0,.75);
}

/* ====================== */
/* SCROLLBAR */
/* ====================== */

.resume-topic-card::-webkit-scrollbar {

  width: 7px;
}

.resume-topic-card::-webkit-scrollbar-thumb {

  background: #9155FD;

  border-radius: 10px;
}

</style>
