<template>
    <div>
      <v-navigation-drawer
        v-model="drawer"
        location="right"
        width="380"
        temporary
        class="add-user-sidebar"
      >
        <div class="sidebar-header">
          <span class="sidebar-title">{{ $t('add') }}</span>
          <v-btn @click="drawer = false" size="small" variant="text" icon>
            <i class="ri-close-line"></i>
          </v-btn>
        </div>
  
        <v-form @submit.prevent="submitAddCategory" ref="form" class="sidebar-form">
          <v-text-field
            v-model="category.name"
            :label="$t('name')"
            :rules="[v => !!v || 'Name is required']"
            required
          />
  
          <div class="sidebar-actions">
            <v-btn color="#9155FD" type="submit" :loading="loading" block>
              {{ $t('add') }}
            </v-btn>
          </div>
        </v-form>
      </v-navigation-drawer>
  
      <v-snackbar v-model="snackbar" :color="snackbarColor" timeout="4000">
        {{ snackbarMessage }}
      </v-snackbar>
    </div>
  </template>
  
  <script setup>
  import HTTP from '@/lib/axios'
  import { ref, watch } from 'vue'
  import { showError } from '@/utils/errorMessageSwal'
  
  const props = defineProps({
    modelValue: Boolean,
    fetchCategories: Function,
  })
  const emit = defineEmits(['update:modelValue'])
  
  const drawer = ref(props.modelValue)
  const form = ref(null)
  const loading = ref(false)
  
  const category = ref({
    name: '',
  })
  
  const snackbar = ref(false)
  const snackbarMessage = ref('')
  const snackbarColor = ref('success')
  
  watch(() => props.modelValue, v => (drawer.value = v))
  
  watch(drawer, v => {
    emit('update:modelValue', v)
    if (!v) resetForm()
  })
  
  function resetForm() {
    category.value = { name: '' }
    form.value?.resetValidation()
  }
  
  function showSnackbar(msg, color = 'success') {
    snackbarMessage.value = msg
    snackbarColor.value = color
    snackbar.value = true
  }
  
  async function submitAddCategory() {
    const valid = await form.value.validate()
    if (!valid.valid) return
  
    loading.value = true
    try {
      const res = await HTTP.post('category/add', category.value)
      props.fetchCategories()
      drawer.value = false
      showSnackbar(res.data.message, 'success')
    } catch (e) {
      showError(e)
    } finally {
      loading.value = false
    }
  }
  </script>
