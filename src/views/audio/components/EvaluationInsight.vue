<template>

  <div class="insight-section">

    <!-- COMMENT -->
    <v-card class="card ai-card">

      <div class="title">
        🤖 Commentaire AI
      </div>

      <p class="comment-text">
        {{ evaluation.commentaire_ai }}
      </p>

    </v-card>

    <!-- POINTS FORTS -->
    <v-card class="card strong">

      <div class="title">
        🟢 Points forts
      </div>

      <div
        v-for="(point, i) in splitText(evaluation.points_forts)"
        :key="i"
        class="point-item strong-item"
      >
        {{ point }}
      </div>

    </v-card>

    <!-- POINTS FAIBLES -->
    <v-card class="card weak">

      <div class="title">
        🔴 Points faibles
      </div>

      <div
        v-for="(point, i) in splitText(evaluation.points_faibles)"
        :key="i"
        class="point-item weak-item"
      >
        {{ point }}
      </div>

    </v-card>

  </div>

</template>

<script setup>

defineProps({
  evaluation: Object
})

function splitText(text) {

  if (!text) return []

  return text
    .split(/[•.-]/)
    .map(t => t.trim())
    .filter(t => t.length)

}

</script>

<style scoped>

.insight-section {

  display: flex;

  flex-direction: column;

  gap: 18px;
}

.card {

  padding: 20px;

  border-radius: 18px;

  transition: all 0.3s ease;

  background: rgb(var(--v-theme-surface));

  color: rgb(var(--v-theme-on-surface));

  box-shadow: 0 4px 18px rgba(0,0,0,0.06);
}

/* COMMENT CARD */

.ai-card {

  border-left: 5px solid #9155FD;
}

/* STRONG */

.strong {

  border-left: 5px solid #4CAF50;
}

/* WEAK */

.weak {

  border-left: 5px solid #F44336;
}

/* TITLES */

.title {

  font-size: 17px;

  font-weight: 700;

  margin-bottom: 15px;

  color: rgb(var(--v-theme-on-surface));
}

/* COMMENT TEXT */

.comment-text {

  line-height: 1.8;

  font-size: 15px;

  color: rgba(var(--v-theme-on-surface), 0.78);
}

/* ITEMS */

.point-item {

  padding: 13px 15px;

  border-radius: 14px;

  margin-bottom: 12px;

  line-height: 1.6;

  font-size: 14px;

  font-weight: 500;

  transition: all 0.25s ease;

  backdrop-filter: blur(4px);
}

.point-item:hover {

  transform: translateY(-2px);
}

/* STRONG ITEM */

.strong-item {

  background: rgba(76, 175, 80, 0.12);

  border-left: 4px solid #4CAF50;

  color: #2e7d32;
}

/* WEAK ITEM */

.weak-item {

  background: rgba(244, 67, 54, 0.12);

  border-left: 4px solid #F44336;

  color: #c62828;
}

/* DARK MODE FIX */

.v-theme--dark .strong-item {

  background: rgba(76, 175, 80, 0.18);

  color: #81C784;
}

.v-theme--dark .weak-item {

  background: rgba(244, 67, 54, 0.18);

  color: #EF9A9A;
}

.v-theme--dark .card {

  box-shadow: 0 4px 18px rgba(0,0,0,0.35);
}

</style>
