<template>

  <div class="score-section">

    <!-- GLOBAL SCORE -->
    <v-card class="score-card">

      <v-progress-circular
        :model-value="evaluation.score_global"
        size="140"
        width="12"
        color="#9155FD"
      >
        <div class="text-center">
          <h1>{{ evaluation.score_global }}%</h1>

          <span>
            Global Score
          </span>
        </div>
      </v-progress-circular>

    </v-card>

    <!-- DETAILS -->
    <v-card class="details-card">

      <div class="score-item">

        <div class="d-flex justify-space-between">
          <span>Politesse</span>
          <span>{{ evaluation.politesse_score }}%</span>
        </div>

        <v-progress-linear
          :model-value="evaluation.politesse_score"
          color="blue"
          rounded
        />

      </div>

      <div class="score-item">

        <div class="d-flex justify-space-between">
          <span>Clarté</span>
          <span>{{ evaluation.clarte_score }}%</span>
        </div>

        <v-progress-linear
          :model-value="evaluation.clarte_score"
          color="orange"
          rounded
        />

      </div>

      <div class="score-item">

        <div class="d-flex justify-space-between">
          <span>Respect Script</span>
          <span>{{ evaluation.respect_script_score }}%</span>
        </div>

        <v-progress-linear
          :model-value="evaluation.respect_script_score"
          color="purple"
          rounded
        />

      </div>

      <div class="score-item">

        <div class="d-flex justify-space-between">
          <span>Satisfaction</span>
          <span>{{ evaluation.satisfaction_client_score }}%</span>
        </div>

        <v-progress-linear
          :model-value="evaluation.satisfaction_client_score"
          color="green"
          rounded
        />

      </div>

    </v-card>

  </div>

</template>

<script setup>

defineProps({
  evaluation: Object
})

</script>

<style scoped>

.score-section {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.score-card {

  padding: 30px;

  display: flex;

  justify-content: center;

  border-radius: 20px;
}

.details-card {

  padding: 20px;

  border-radius: 20px;
}

.score-item {
  margin-bottom: 18px;
}

</style>
