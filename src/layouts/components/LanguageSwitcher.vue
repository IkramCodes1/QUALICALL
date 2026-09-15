<script setup>
import { useI18n } from 'vue-i18n'
import { onMounted, watch } from 'vue'

const { locale } = useI18n()

// تحميل اللغة من localStorage
onMounted(() => {
  const savedLang = localStorage.getItem('lang')
  if (savedLang) locale.value = savedLang
})

// حفظ اللغة ملي كتبدل
watch(locale, (newLang) => {
  localStorage.setItem('lang', newLang)
})

// function تبديل اللغة
const changeLang = lang => {
  locale.value = lang
}
</script>

<template>
  <VMenu>
    <template #activator="{ props }">
      <VBtn v-bind="props" icon>
        <VIcon icon="ri-translate-2" />
      </VBtn>
    </template>

    <VList>
      <VListItem @click="changeLang('en')" title="English" />
      <VListItem @click="changeLang('fr')" title="Français" />
    </VList>
  </VMenu>
</template>
