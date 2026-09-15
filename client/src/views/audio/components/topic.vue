<template>

  <v-card
    class="topic-card"
    rounded="xl"
    elevation="4"
  >

    <!-- HEADER -->
    <div class="header">

      <div>
        <h3 class="text-h6 font-weight-bold">
          Topics
        </h3>

        <p class="text-caption text-grey">
          AI detected segments
        </p>
      </div>

      <v-chip
        size="small"
        color="#9155FD"
      >
        {{ topics.length }}
      </v-chip>

    </div>

    <!-- TOPICS -->
    <div class="topics-container">

      <div
        v-for="(topic, i) in coloredTopics"
        :key="i"
        class="topic-item"
        :style="{
          borderLeft:
            `5px solid ${topic.color}`
        }"
        @click="$emit(
          'select-topic',
          topic
        )"
      >

        <!-- TOP -->
        <div
          class="d-flex
                 justify-space-between
                 align-center"
        >

          <div class="topic-title">

            <v-icon
              size="18"
              class="mr-2"
              :style="{
                color: topic.color
              }"
            >
              ri-chat-voice-line
            </v-icon>

            {{ topic.name }}

          </div>

          <v-btn
            icon
            size="small"
            variant="text"
          >
            <v-icon>
              ri-play-circle-line
            </v-icon>
          </v-btn>

        </div>

        <!-- FOOTER -->
        <div class="topic-footer">

          <v-chip
            size="x-small"
            color="grey-darken-3"
          >
            {{ formatTime(topic.start_time) }}

            -

            {{ formatTime(topic.end_time) }}
          </v-chip>

          <span class="duration">

            {{
              calcDuration(
                topic.start_time,
                topic.end_time
              )
            }}

          </span>

        </div>

      </div>

      <!-- EMPTY -->
      <div
        v-if="!topics || topics.length === 0"
        class="empty-state"
      >

        <v-icon size="40">
          ri-file-list-3-line
        </v-icon>

        <p>
          Aucun topic disponible
        </p>

      </div>

    </div>

  </v-card>

</template>

<script setup>

import { computed } from 'vue'

const props = defineProps({

  topics: {
    type: Array,
    default: () => []
  }
})

const colors = [

  '#9155FD',
  '#26A69A',
  '#EF5350',
  '#FFA726',
  '#42A5F5',
  '#66BB6A',
  '#AB47BC',
  '#EC407A'
]

const coloredTopics = computed(() => {

  return props.topics.map(
    (topic, index) => ({

      ...topic,

      color:
        colors[index % colors.length]
    })
  )
})

function formatTime(seconds) {

  if (!seconds) return '0:00'

  const m =
    Math.floor(seconds / 60)

  const s =
    Math.floor(seconds % 60)

  return `${m}:${
    s.toString().padStart(2,'0')
  }`
}

function calcDuration(start, end) {

  const total =
    Math.floor(end - start)

  return `${total}s`
}

</script>

<style scoped>

.topic-card {

  height: calc(100vh - 220px);

  display: flex;

  flex-direction: column;

  overflow: hidden;

  background:
    linear-gradient(
      135deg,
      #1f1f2e,
      #2b2b45
    );

  color: white;
}

/* HEADER */

.header {

  padding: 20px;

  display: flex;

  justify-content: space-between;

  align-items: center;

  border-bottom:
    1px solid rgba(255,255,255,.08);
}

/* CONTAINER */

.topics-container {

  flex: 1;

  overflow-y: auto;

  padding: 18px;
}

/* ITEM */

.topic-item {

  background:
    rgba(255,255,255,.05);

  border-radius: 18px;

  padding: 16px;

  margin-bottom: 16px;

  cursor: pointer;

  transition: all .25s ease;

  border:
    1px solid rgba(255,255,255,.05);
}

.topic-item:hover {

  transform: translateY(-3px);

  background:
    rgba(255,255,255,.08);

  box-shadow:
    0 0 18px rgba(0,0,0,.25);
}
.topic-item.active {
  background: rgba(145,85,253,.18);
  border: 1px solid #9155FD;
}

/* TITLE */

.topic-title {

  display: flex;

  align-items: center;

  font-weight: 700;

  font-size: 15px;

  max-width: 85%;
}

/* FOOTER */

.topic-footer {

  margin-top: 14px;

  display: flex;

  justify-content: space-between;

  align-items: center;
}

.duration {

  font-size: 12px;

  opacity: .7;
}

/* EMPTY */

.empty-state {

  height: 300px;

  display: flex;

  flex-direction: column;

  justify-content: center;

  align-items: center;

  gap: 12px;

  opacity: .7;
}

/* SCROLLBAR */

.topics-container::-webkit-scrollbar {

  width: 8px;
}

.topics-container::-webkit-scrollbar-thumb {

  background: #9155FD;

  border-radius: 10px;
}

</style>
