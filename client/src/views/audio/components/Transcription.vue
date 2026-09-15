<template>

  <v-card
    class="transcription-card"
    rounded="xl"
    elevation="4"
  >

    <!-- HEADER -->
    <div class="header">

      <div>
        <h3 class="text-h6 font-weight-bold">
          Transcription
        </h3>

        <p class="text-caption subtitle">
          AI Generated Conversation
        </p>
      </div>

      <!-- SEARCH -->
      <v-text-field
        v-model="search"
        density="compact"
        variant="outlined"
        type="text"
        :placeholder="$t('search')"
        prepend-inner-icon="ri-search-line"
        hide-details
        style="max-width:220px"
      />

    </div>

    <!-- CONTENT -->
    <div class="transcription-content">

      <div
        v-for="(line, i) in filteredTranscription"
        :key="i"
        class="line"
        :class="{ active: activeIndex === i }"
        @click="$emit('seek', line.time)"
      >

        <!-- TOP -->
        <div
          class="d-flex justify-space-between align-center mb-1"
        >

          <!-- SPEAKER -->
          <div class="speaker">

            <span class="speaker-name">
              {{ line.speaker }}
            </span>

          </div>

          <!-- TIME -->
          <v-chip
            size="small"
            class="time-chip"
          >
            {{ formatTime(line.time) }}
          </v-chip>

        </div>

        <!-- TEXT -->
        <div class="text">
          {{ line.text }}
        </div>

      </div>

    </div>

  </v-card>

</template>

<script setup>

import {
  computed,
  watch,
  nextTick,
  ref
} from 'vue'

const props = defineProps({
  transcription: Array,
  currentTime: Number
})

const search = ref('')

const filteredTranscription =
  computed(() => {

    if (!search.value) {
      return props.transcription
    }

    return props.transcription.filter(line =>
      line.text
        ?.toLowerCase()
        .includes(search.value.toLowerCase())
    )
  })

const activeIndex = computed(() => {

  for (
    let i = 0;
    i < filteredTranscription.value.length;
    i++
  ) {

    const curr =
      filteredTranscription.value[i]

    const next =
      filteredTranscription.value[i + 1]

    if (
      props.currentTime >= curr.time &&
      (!next ||
       props.currentTime < next.time)
    ) {
      return i
    }
  }

  return null
})

watch(activeIndex, async (val) => {

  await nextTick()

  const el =
    document.querySelectorAll('.line')[val]

  if (el) {

    el.scrollIntoView({
      behavior: 'smooth',
      block: 'center'
    })
  }
})

function formatTime(seconds) {

  const mins =
    Math.floor(seconds / 60)

  const secs =
    Math.floor(seconds % 60)

  return `${mins}:${
    secs.toString().padStart(2,'0')
  }`
}

</script>

<style scoped>

.transcription-card {

  height: 650px;

  display: flex;

  flex-direction: column;

  overflow: hidden;

  transition: .3s ease;
}

/* ===================== */
/* DARK MODE */
/* ===================== */

.v-theme--dark .transcription-card {

  background:
    linear-gradient(
      135deg,
      #1f1f2e,
      #2b2b45
    );

  color: white;
}

.v-theme--dark .header {

  border-bottom:
    1px solid rgba(255,255,255,.08);
}

.v-theme--dark .subtitle {

  color:
    rgba(255,255,255,.6);
}

.v-theme--dark .line {

  background:
    rgba(255,255,255,.04);

  border:
    1px solid transparent;
}

.v-theme--dark .line:hover {

  transform: translateY(-2px);

  background:
    rgba(255,255,255,.07);
}

.v-theme--dark .line.active {

  background:
    rgba(145,85,253,.18);

  border:
    1px solid #9155FD;

  box-shadow:
    0 0 18px rgba(145,85,253,.35);
}

.v-theme--dark .text {

  color:
    rgba(255,255,255,.88);
}

.v-theme--dark .time-chip {

  background:
    rgba(255,255,255,.08);

  color: white;
}

/* ===================== */
/* LIGHT MODE */
/* ===================== */

.v-theme--light .transcription-card {

  background:
    linear-gradient(
      135deg,
      #ffffff,
      #f4f5fa
    );

  color: #2b2b45;
}

.v-theme--light .header {

  border-bottom:
    1px solid rgba(0,0,0,.08);
}

.v-theme--light .subtitle {

  color:
    rgba(0,0,0,.55);
}

.v-theme--light .line {

  background:
    rgba(0,0,0,.03);

  border:
    1px solid transparent;
}

.v-theme--light .line:hover {

  transform: translateY(-2px);

  background:
    rgba(0,0,0,.05);
}

.v-theme--light .line.active {

  background:
    rgba(145,85,253,.10);

  border:
    1px solid #9155FD;

  box-shadow:
    0 0 14px rgba(145,85,253,.18);
}

.v-theme--light .text {

  color:
    rgba(0,0,0,.75);
}

.v-theme--light .time-chip {

  background:
    #ececf3;

  color:
    #2b2b45;
}

/* ===================== */
/* COMMON */
/* ===================== */

.header {

  padding: 20px;

  display: flex;

  justify-content: space-between;

  align-items: center;
}

.transcription-content {

  flex: 1;

  overflow-y: auto;

  padding: 20px;
}

.line {

  padding: 16px;

  border-radius: 18px;

  margin-bottom: 14px;

  cursor: pointer;

  transition: all .25s ease;
}

.speaker {

  display: flex;

  align-items: center;

  gap: 10px;
}

.speaker-name {

  font-weight: 600;
}

.text {

  line-height: 1.7;
}

/* SCROLLBAR */

.transcription-content::-webkit-scrollbar {

  width: 8px;
}

.transcription-content::-webkit-scrollbar-thumb {

  background: #9155FD;

  border-radius: 10px;
}

</style>
