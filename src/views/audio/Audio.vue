<template>
  <v-container fluid>

    <!-- 🎧 PLAYER -->
    <AudioPlayer ref="playerRef" :audioSrc="audioSrc" @update-time="currentTime = $event" />
    
    <!-- 🤖 AI BUTTON -->
    <div
    v-if="!(
  Number(conversation?.is_transcripted) === 1 &&
  Number(conversation?.is_evaluate) === 1 &&
  conversation?.note_ai != null &&
  conversation?.note_ai !== ''
)"
  class="d-flex justify-center my-4"
>
  <v-btn
    color="#9155FD"
    :loading="loadingAI"
    @click="generateAI"
  >
    {{ $t('generateAiAnalis') }}
  </v-btn>
</div>

    <v-row>
      <!-- 📝 TRANSCRIPTION -->
      <v-col cols="12" md="7">
        <Transcription :transcription="transcription" :currentTime="currentTime" @seek="handleSeek" />
      </v-col>

      <!-- 📊 SIDE PANEL -->
      <v-col cols="12" md="5">
        <v-card>
          <v-tabs v-model="tab" color="#9155FD">
            <v-tab value="resume">{{ $t('GeneralSummary') }}</v-tab>

            <v-tab value="topicResume">{{ $t('SequenceSummary') }}</v-tab>
            <v-tab value="ai">Call Scoring</v-tab>
            <v-tab value="aiComment">Conversation Analysis</v-tab>
          </v-tabs>

          <v-window v-model="tab">

            <!-- ✅ Resume Générale -->
            <v-window-item value="resume">
              <Resume :resume="resumeText" />
            </v-window-item>

            <!-- ✅ Séquence Resume -->
            <v-window-item value="topicResume">
              <ResumeTopic :topics="topics" :resumeTopics="resumeTopics" @seek="handleSeek" />
            </v-window-item>

            <v-window-item value="ai">
              <EvaluationAI :evaluation="evaluationData" />
            </v-window-item>
            <v-window-item value="aiComment">
              <EvaluationInsight :evaluation="evaluationData" />
            </v-window-item>
            


          </v-window>
        </v-card>
      </v-col>
    </v-row>

  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'

import AudioPlayer from './components/AudioPlayer.vue'
import Transcription from './components/Transcription.vue'
import Resume from './components/Resume.vue'
import EvaluationAI from './components/EvaluationAI.vue'
import EvaluationInsight  from './components/EvaluationInsight.vue'
import TopicResume from './components/topic.vue'
import ResumeTopic from './components/ResumeTopic.vue'

const audioSrc = ref('')
const currentTime = ref(0)
const tab = ref('resume')
const playerRef = ref(null)
const route = useRoute()

const transcription = ref([])
const callData = ref({})
const resumeText = ref('')   // texte, pas tableau
const aiScore = ref(0)
const loadingAI = ref(false)

const topics = ref([])
const resumeTopics = ref([])
const colors = [
  '#9155FD', '#26A69A', '#EF5350', '#FFA726',
  '#42A5F5', '#66BB6A', '#AB47BC', '#EC407A'
]

const evaluationData = ref({})
const conversation = ref({})

// 🎯 Fonctions globales
function handleSeek(time) {
  if (playerRef.value) {
    playerRef.value.seekTo(time)
  }
}

async function generateAI() {

try {

  loadingAI.value = true

  await axios.post(
    "http://127.0.0.1:8080/process-audio",
    {
      conversation_id: route.params.id,
      path: audioSrc.value
    }
  )

  // REFRESH PAGE
  window.location.reload()

} catch (err) {

  console.error(err)

} finally {

  loadingAI.value = false
}
}

// 🚀 Chargement initial
onMounted(async () => {
  try {
    const res = await axios.get(
      `http://localhost:8000/api/conversation/show/${route.params.id}`
    )
    const data = res.data.data || res.data
    conversation.value = data

    // 🎧 AUDIO
    if (data.path) {
      const filename = data.path.split('/').pop()
      audioSrc.value = `http://localhost:8000/api/audio/${filename}`
    }

    // 📝 TRANSCRIPTION
    transcription.value = (data.dialogues || []).map(d => ({
      time: Number(d.start) || 0,
      text: d.text || '',
      speaker: d.actor || 'Speaker'
    }))

    // 📊 DETAILS
    callData.value = {
      produit: data.name || 'Sans nom',
      date: data.call_date || '',
      agent: 'Agent'
    }

    // 🤖 AI
    aiScore.value = data.evaluation?.score_global || 0
    evaluationData.value = data.evaluation || {}

    // 🧾 RESUME
    resumeText.value = [
      {
        text: data.resume_general
      }
    ]

    // 🎨 Topics
    topics.value = (data.topics || []).map((topic, index) => ({
      ...topic,
      color: colors[index % colors.length]
    }))

    resumeTopics.value = data.resume_topics || []
  } catch (err) {
    console.error('ERROR:', err)
  }
})

</script>
<style scoped>
.topic-chip {
  padding: 10px 18px;
  border-radius: 999px;
  font-size: 13px;
  font-weight: 600;

  box-shadow: 0 4px 12px rgba(0, 0, 0, .2);

  transition: all .25s ease;
}

.topic-chip:hover {
  transform: translateY(-2px);
}
</style>
