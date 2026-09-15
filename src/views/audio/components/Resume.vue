<template>
  <v-card class="resume-card">

    <div
      v-for="(item, i) in resume"
      :key="i"
      class="resume-line"
    >
      {{ item }}
    </div>

    <div v-if="!resume || resume.length === 0">
      Aucun résumé disponible
    </div>

  </v-card>
</template>

<script setup>

defineProps({
  resume: {
    type: Array,
    default: () => []
  }
})

</script>

<style scoped>

.resume-card {
  height: 400px;
  overflow-y: auto;
  padding: 10px;
}

.resume-line {
  padding: 10px;
  margin-bottom: 10px;
  background: #9277bd;
  border-radius: 8px;
  color: white;
}

</style>
