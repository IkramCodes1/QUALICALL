<template>
  <v-container>
    <v-row>
      <!-- Audio Player Card -->
      <v-col cols="12" md="6">
        <v-card class="audio-card">
          <div class="audio-bg" @mouseenter="hover = true" @mouseleave="hover = false">
            <!-- Horizontal controls row -->
            <div class="audio-controls-row">
              <v-btn
                icon
                class="rewind-btn audio-btn"
                @click="seek(-10)"
                color="white"
                size="large"
                :class="{ 'active-btn': hover }"
              >
                <i class="ri-arrow-left-double-line"></i>
              </v-btn>
              <v-btn
                icon
                class="play-btn audio-btn"
                @click="togglePlay"
                color="white"
                size="x-large"
                :class="{ 'active-btn': hover }"
              >
                <i v-if="isPlaying" class="ri-stop-circle-line"></i>
                <i v-else class="ri-play-circle-line"></i>
              </v-btn>
              <v-btn
                icon
                class="forward-btn audio-btn"
                @click="seek(10)"
                color="white"
                size="large"
                :class="{ 'active-btn': hover }"
              >
                <i class="ri-arrow-right-double-line"></i>
              </v-btn>
            </div>
            <!-- Time display, moved below controls and made responsive -->
            <div class="audio-time-row">
              <span class="audio-time">
                {{ formatTime(currentTime) }}
              </span>
              <span class="audio-time audio-time-divider">/</span>
              <span class="audio-time audio-time-duration">
                {{ formatTime(duration) }}
              </span>
            </div>
            <!-- Progress bar in minutes -->
            <v-slider
              class="audio-progress-bar"
              v-model="sliderTimeMinutes"
              :max="durationMinutes"
              :min="0"
              step="0.01"
              hide-details
              @update:modelValue="onSliderChangeMinutes"
              :thumb-label="true"
              color="#fff"
              track-color="white"
              thumb-color="#9155FD"
            >
              <template #thumb-label="{ modelValue }">
                {{ formatMinutes(modelValue) }}
              </template>
            </v-slider>
            <!-- Volume and speed controls row -->
            <div class="audio-controls-bar">
              <!-- Volume controls -->
              <v-icon
                class="audio-vol-icon"
                color="white"
                size="20"
                style="margin-right: 4px;"
              >{{ volume <= 0 ? 'ri-volume-mute-line' : (volume < 0.5 ? 'ri-volume-down-line' : 'ri-volume-up-line') }}</v-icon>
              <v-slider
                class="audio-vol-slider"
                v-model="volume"
                :min="0"
                :max="1"
                step="0.01"
                hide-details
                style="max-width: 100px; margin: 0 8px;"
                color="#fff"
                track-color="white"
                thumb-color="#9155FD"
              ></v-slider>
              <span class="audio-vol-value">{{ Math.round(volume * 100) }}</span>
              <!-- Speed controls -->
              <!-- Desktop: show +/- buttons and value, Mobile: show dropdown only -->
              <template v-if="!isMobile">
                <v-btn
                  icon
                  class="audio-speed-btn"
                  @click="decreaseSpeed"
                  size="small"
                  :disabled="playbackRate <= 0.75"
                >
                  <i class="ri-arrow-left-s-line"></i>
                </v-btn>
                <span class="audio-speed-value">x{{ playbackRate.toFixed(2).replace(/\.00$/, '') }}</span>
                <v-btn
                  icon
                  class="audio-speed-btn"
                  @click="increaseSpeed"
                  size="small"
                  :disabled="playbackRate >= 2"
                >
                  <i class="ri-arrow-right-s-line"></i>
                </v-btn>
              </template>
              <template v-else>
                <v-menu
                  v-model="speedMenu"
                  :close-on-content-click="true"
                  offset-y
                  transition="scale-transition"
                >
                  <template #activator="{ props }">
                    <v-btn
                      v-bind="props"
                      class="audio-speed-dropdown-btn"
                      color="white"
                      style="min-width: 48px; font-weight: bold;"
                      variant="text"
                    >
                      x{{ playbackRate.toFixed(2).replace(/\.00$/, '') }}
                    </v-btn>
                  </template>
                  <v-list>
                    <v-list-item
                      v-for="rate in speedOptions"
                      :key="rate"
                      @click="setSpeed(rate)"
                    >
                      <v-list-item-title>
                        x{{ rate.toFixed(2).replace(/\.00$/, '') }}
                      </v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
              </template>
            </div>
          </div>
          <audio
            ref="audioPlayer"
            :src="audioSrc"
            style="display: none;"
            @timeupdate="onTimeUpdate"
            @loadedmetadata="onLoadedMetadata"
            @ended="onEnded"
          ></audio>
        </v-card>
      </v-col>

      <!-- Info & Transcription Card (responsive) -->
      <v-col cols="12" md="6">
        <v-card class="audio-side-card" elevation="6">
          <v-tabs
            v-model="sideTab"
            background-color="transparent"
            color="#9155FD"
            grow
            class="audio-side-tabs"
            :show-arrows="true"
          >
            <template #prev>
              <v-btn icon class="audio-tabs-arrow-btn" @click="goToPrevTab" :disabled="!canGoPrev">
                <i class="ri-arrow-left-s-line"></i>
              </v-btn>
            </template>
            <template #next>
              <v-btn icon class="audio-tabs-arrow-btn" @click="goToNextTab" :disabled="!canGoNext">
                <i class="ri-arrow-right-s-line"></i>
              </v-btn>
            </template>
            <v-tab value="info">
              <i class="ri-information-line" style="margin-right: 6px;"></i>
              Info
            </v-tab>
            <v-tab value="resume">
              <i class="ri-file-text-line" style="margin-right: 6px;"></i>
              Resume
            </v-tab>
            <v-tab value="transcription">
              <i class="ri-file-text-line" style="margin-right: 6px;"></i>
              Transcription
            </v-tab>
            <v-tab value="evaluation-ai">
              <i class="ri-file-text-line" style="margin-right: 6px;"></i>
              Evaluation AI
            </v-tab>
            <v-tab value="evaluation">
              <i class="ri-file-text-line" style="margin-right: 6px;"></i>
              Evaluation
            </v-tab>
          </v-tabs>
          <v-window v-model="sideTab" class="audio-side-window">
            <v-window-item value="info">
              hello info
            </v-window-item>
            <v-window-item value="transcription">
              hello transcription
            </v-window-item>
            <v-window-item value="resume">
              hello resume
            </v-window-item>
            <v-window-item value="evaluation-ai">
              evaluation AI 
            </v-window-item>
            <v-window-item value="evaluation">
              evaluation 
            </v-window-item>
          </v-window>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue'
import audioFile from './1104918.wav'

const audioSrc = ref(audioFile)
const audioPlayer = ref(null)
const isPlaying = ref(false)
const currentTime = ref(0)
const duration = ref(0)
const hover = ref(false)
const volume = ref(1)
const playbackRate = ref(1)
const speedMenu = ref(false)
const isMobile = ref(false)

const speedOptions = [0.75, 1, 1.25, 1.5, 1.75, 2]

// For minute-based slider
const sliderTimeMinutes = ref(0)
const durationMinutes = computed(() => duration.value / 60)

const sliderTime = ref(0) // keep for internal logic
let isSeeking = false

// Side panel tab (info or transcription)
const sideTab = ref('info')

// Tabs order for navigation
const tabList = [
  'info',
  'resume',
  'transcription',
  'evaluation-ai',
  'evaluation'
]

const currentTabIndex = computed(() => tabList.indexOf(sideTab.value))
const canGoPrev = computed(() => currentTabIndex.value > 0)
const canGoNext = computed(() => currentTabIndex.value < tabList.length - 1)

function goToPrevTab() {
  if (canGoPrev.value) {
    sideTab.value = tabList[currentTabIndex.value - 1]
  }
}
function goToNextTab() {
  if (canGoNext.value) {
    sideTab.value = tabList[currentTabIndex.value + 1]
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleKeydown)
  // Set initial volume and playbackRate when audio is ready
  if (audioPlayer.value) {
    audioPlayer.value.volume = volume.value
    audioPlayer.value.playbackRate = playbackRate.value
  }
  // Detect mobile
  isMobile.value = /Mobi|Android/i.test(navigator.userAgent)
})

onBeforeUnmount(() => {
  window.removeEventListener('keydown', handleKeydown)
})

watch(volume, (val) => {
  if (audioPlayer.value) {
    audioPlayer.value.volume = val
  }
})

watch(playbackRate, (val) => {
  if (audioPlayer.value) {
    audioPlayer.value.playbackRate = val
  }
})

watch(currentTime, (val) => {
  if (!isSeeking) {
    sliderTime.value = val
    sliderTimeMinutes.value = val / 60
  }
})

watch(duration, (val) => {
  // Update durationMinutes if duration changes
  sliderTimeMinutes.value = currentTime.value / 60
})

function formatTime(seconds) {
  if (isNaN(seconds)) return '00:00'
  const min = Math.floor(seconds / 60)
  const sec = Math.floor(seconds % 60)
  return `${min.toString().padStart(2, '0')}:${sec.toString().padStart(2, '0')}`
}

function formatMinutes(minutes) {
  if (isNaN(minutes)) return '00:00'
  const min = Math.floor(minutes)
  const sec = Math.floor((minutes - min) * 60)
  return `${min.toString().padStart(2, '0')}:${sec.toString().padStart(2, '0')}`
}

function handleKeydown(e) {
  if (e.key === 'ArrowRight') {
    seek(10)
  } else if (e.key === 'ArrowLeft') {
    seek(-10)
  }
}

function togglePlay() {
  if (!audioPlayer.value) return
  if (isPlaying.value) {
    audioPlayer.value.pause()
  } else {
    audioPlayer.value.play()
  }
  isPlaying.value = !isPlaying.value
}

function seek(seconds) {
  if (!audioPlayer.value) return
  let newTime = audioPlayer.value.currentTime + seconds
  if (newTime < 0) newTime = 0
  if (newTime > duration.value) newTime = duration.value
  audioPlayer.value.currentTime = newTime
  currentTime.value = newTime
}

function onEnded() {
  isPlaying.value = false
  currentTime.value = 0
}

function onTimeUpdate(e) {
  if (!isSeeking) {
    currentTime.value = audioPlayer.value.currentTime
  }
}

function onLoadedMetadata(e) {
  duration.value = audioPlayer.value.duration
}

function increaseSpeed() {
  if (playbackRate.value < 2) {
    let next = Math.round((playbackRate.value + 0.25) * 100) / 100
    if (next > 2) next = 2
    playbackRate.value = next
  }
}
function decreaseSpeed() {
  if (playbackRate.value > 0.75) {
    let next = Math.round((playbackRate.value - 0.25) * 100) / 100
    if (next < 0.75) next = 0.75
    playbackRate.value = next
  }
}

function setSpeed(rate) {
  playbackRate.value = rate
  speedMenu.value = false
}

// Progress bar logic for minutes
function onSliderChangeMinutes(val) {
  isSeeking = true
  sliderTimeMinutes.value = val
  const seconds = val * 60
  sliderTime.value = seconds
  if (audioPlayer.value) {
    audioPlayer.value.currentTime = seconds
    currentTime.value = seconds
  }
  setTimeout(() => {
    isSeeking = false
  }, 100)
}
</script>

<style scoped>
.audio-card {
  overflow: hidden;
  width: 100%;
}
.audio-bg {
  background: linear-gradient(135deg, #6a82fb 0%, #fc5c7d 100%);
  display: flex;
  flex-direction: column;
  align-items: stretch;
  justify-content: flex-start;
  height: 300px;
  position: relative;
  padding: 32px 32px 24px 32px;
}
.audio-controls-row {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 18px;
  margin-bottom: 8px;
}
.audio-btn {
  opacity: 1;
  pointer-events: auto;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: opacity 0.2s;
}
.play-btn {
  z-index: 2;
  margin: 0 8px;
}
.rewind-btn, .forward-btn {
  z-index: 2;
}
.audio-time {
  font-family: monospace;
  font-weight: bold;
  color: #fff;
  margin-left: 18px;
  min-width: 90px;
  text-align: right;
}
.audio-progress-bar {
  margin: 15px 0 8px 0;
  --v-slider-track-size: 6px;
  --v-slider-thumb-size: 18px;
}
.audio-controls-bar {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 12px;
  background: rgba(255,255,255,0.08);
  border-radius: 16px;
  padding: 8px 18px;
  font-size: 16px;
  z-index: 3;
  margin-top: 8px;
}
.audio-vol-icon {
  color: #fff !important;
}
.audio-vol-slider {
  min-width: 60px;
  max-width: 100px;
}
.audio-vol-value, .audio-speed-value {
  color: #fff;
  font-size: 15px;
  min-width: 32px;
  text-align: center;
  font-family: monospace;
}
.audio-speed-btn {
  color: #fff !important;
  min-width: 32px;
}

/* Side card styles */
.audio-side-card {
  width: 100%;
  min-height: 300px;
  border-radius: 18px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}
.audio-side-tabs {
  border-bottom: 1px solid #eee;
}
.audio-tabs-arrow-btn {
  color: #9155FD !important;
  background: #f5f5f5 !important;
  margin: 0 2px;
}
.audio-side-window {
  flex: 1 1 auto;
  min-height: 600px;
}
.audio-side-content {
  padding: 18px 20px 18px 20px;
}
</style>
