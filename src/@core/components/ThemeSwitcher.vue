<script setup>
import { useTheme } from 'vuetify'

const props = defineProps({
  themes: {
    type: Array,
    required: true,
  },
})

const {
  name: themeName,
  global: globalTheme,
} = useTheme()

const {
  state: currentThemeName,
  next: getNextThemeName,
  index: currentThemeIndex,
} = useCycleList(props.themes.map(t => t.name), { initialValue: themeName })

const changeTheme = () => {
  globalTheme.name.value = getNextThemeName()
  localStorage.setItem('selected-theme', getNextThemeName()) 
  window.dispatchEvent(new Event('theme-changed')) 

}

onMounted(() => {
  const savedTheme = localStorage.getItem('selected-theme')
  if (savedTheme && props.themes.some(t => t.name === savedTheme)) {
    globalTheme.name.value = savedTheme
    currentThemeName.value = savedTheme
  }
})

// Update icon if theme is changed from other sources
watch(() => globalTheme.name.value, val => {
  currentThemeName.value = val
  localStorage.setItem('selected-theme', val) 
  window.dispatchEvent(new Event('theme-changed')) 


})
</script>

<template>
  <IconBtn @click="changeTheme">
    <VIcon :icon="props.themes[currentThemeIndex].icon" />
    <VTooltip
      activator="parent"
      open-delay="1000"
      scroll-strategy="close"
    >
      <span class="text-capitalize">{{ currentThemeName }}</span>
    </VTooltip>
  </IconBtn>
</template>
