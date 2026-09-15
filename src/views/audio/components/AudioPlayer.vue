<template>
  <v-card
    class="audio-player pa-4 mb-4"
    rounded="xl"
    elevation="4"
  >

    <!-- TOP -->
    <div class="d-flex justify-space-between align-center mb-3">

      <div>
        <h3 class="text-h6 font-weight-bold">
          Call Audio
        </h3>

        <p class="text-caption text-grey">
          AI Conversation Analysis
        </p>
      </div>

      <!-- SPEED -->
      <v-select
        v-model="playbackRate"
        :items="[0.5,1,1.25,1.5,2]"
        density="compact"
        hide-details
        variant="outlined"
        style="max-width:100px"
      />
    </div>

    <!-- WAVE -->
    <div id="waveform"></div>

    <!-- CONTROLS -->
    <div class="d-flex align-center justify-space-between mt-4">

      <!-- LEFT -->
      <div class="d-flex align-center ga-2">

        <!-- PLAY -->
        <v-btn
          icon
          color="#9155FD"
          size="large"
          @click="playPause"
        >
          <i
            :class="isPlaying
              ? 'ri-pause-line'
              : 'ri-play-line'"
          />
        </v-btn>

        <!-- TIME -->
        <div>
          <div class="text-body-2 font-weight-medium">
            {{ formatTime(currentTime) }}
          </div>

          <div class="text-caption text-grey">
            {{ formatTime(duration) }}
          </div>
        </div>

      </div>

      <!-- RIGHT -->
      <div class="d-flex align-center ga-3">

        <!-- VOLUME -->
        <v-icon>
          ri-volume-up-line
        </v-icon>

        <v-slider
          v-model="volume"
          max="1"
          min="0"
          step="0.1"
          hide-details
          density="compact"
          style="width:120px"
        />

      </div>

    </div>

  </v-card>
</template>

<script setup>

import {
  ref,
  onMounted,
  watch,
  onBeforeUnmount
} from 'vue'

import WaveSurfer from 'wavesurfer.js'

const props = defineProps({
  audioSrc: String
})

const emit = defineEmits(['update-time'])

const waveSurfer = ref(null)

const isPlaying = ref(false)

const currentTime = ref(0)

const duration = ref(0)

const volume = ref(1)

const playbackRate = ref(1)

onMounted(() => {

  waveSurfer.value = WaveSurfer.create({

    container: '#waveform',

    waveColor: '#d1d5db',

    progressColor: '#9155FD',

    cursorColor: '#9155FD',

    barWidth: 3,

    barRadius: 4,

    height: 90,

    responsive: true
  })

  if (props.audioSrc) {
    waveSurfer.value.load(props.audioSrc)
  }

  waveSurfer.value.on('ready', () => {

    duration.value =
      waveSurfer.value.getDuration()

    waveSurfer.value.setVolume(volume.value)

    waveSurfer.value.setPlaybackRate(
      playbackRate.value
    )
  })

  waveSurfer.value.on('audioprocess', () => {

    currentTime.value =
      waveSurfer.value.getCurrentTime()

    emit('update-time', currentTime.value)
  })

  waveSurfer.value.on('seek', () => {

    currentTime.value =
      waveSurfer.value.getCurrentTime()

    emit('update-time', currentTime.value)
  })

  waveSurfer.value.on('play', () => {
    isPlaying.value = true
  })

  waveSurfer.value.on('pause', () => {
    isPlaying.value = false
  })

  waveSurfer.value.on('finish', () => {
    isPlaying.value = false
  })
})

watch(volume, (val) => {

  if (waveSurfer.value) {
    waveSurfer.value.setVolume(val)
  }
})

watch(playbackRate, (val) => {

  if (waveSurfer.value) {
    waveSurfer.value.setPlaybackRate(val)
  }
})

watch(() => props.audioSrc, (newVal) => {

  if (newVal && waveSurfer.value) {
    waveSurfer.value.load(newVal)
  }
})

function playPause() {

  if (!waveSurfer.value) return

  waveSurfer.value.playPause()
}

function seekTo(time) {

  if (!waveSurfer.value) return

  const total =
    waveSurfer.value.getDuration()

  if (!total) return

  waveSurfer.value.seekTo(time / total)

  waveSurfer.value.play()
}

function formatTime(seconds) {

  if (!seconds) return '00:00'

  const mins =
    Math.floor(seconds / 60)

  const secs =
    Math.floor(seconds % 60)

  return `${mins}:${
    secs < 10 ? '0' : ''
  }${secs}`
}

defineExpose({
  seekTo
})

onBeforeUnmount(() => {

  if (waveSurfer.value) {
    waveSurfer.value.destroy()
  }
})

</script>

<style scoped>

/* ====================== */
/* COMMON */
/* ====================== */

.audio-player {

  transition: .3s ease;

  border-radius: 24px;

  backdrop-filter: blur(12px);
}

/* ====================== */
/* DARK MODE */
/* ====================== */

.v-theme--dark .audio-player {

  background:
    linear-gradient(
      135deg,
      #1e1e2f,
      #2b2b45
    );

  color: white;

  border:
    1px solid rgba(255,255,255,.08);
}

.v-theme--dark #waveform {

  background:
    rgba(255,255,255,.03);

  border-radius: 18px;

  padding: 10px;
}

/* ====================== */
/* LIGHT MODE */
/* ====================== */

.v-theme--light .audio-player {

  background:
    linear-gradient(
      135deg,
      #ffffff,
      #f5f7ff
    );

  color: #2b2b45;

  border:
    1px solid rgba(0,0,0,.08);

  box-shadow:
    0 8px 25px rgba(0,0,0,.05);
}

.v-theme--light #waveform {

  background:
    rgba(0,0,0,.03);

  border-radius: 18px;

  padding: 10px;
}

/* ====================== */
/* EXTRA UI */
/* ====================== */

.v-theme--light .text-grey {

  color:
    rgba(0,0,0,.55) !important;
}

.v-theme--dark .text-grey {

  color:
    rgba(255,255,255,.6) !important;
}

</style>
