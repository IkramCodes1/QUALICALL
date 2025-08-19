<!-- Exemple dans AddUsers.vue ou UpdateUsers.vue -->
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
        <span class="sidebar-title">{{ $t('edit') }} {{ selectedUser.name }}</span>
        <v-btn @click="drawer = false" size="small" variant="text" icon>
          <i class="ri-close-line"></i>
        </v-btn>
      </div>
      <v-form @submit.prevent="submitUpdateUser" ref="addUserForm" class="sidebar-form">
        <v-text-field
          v-model="userUpdate.name"
          :label=" $t('fullName') "
          :rules="[
            v => !!v || $t('validation.fullNameRequired')
          ]"
          required
          class="mb-3"
        />
        <v-text-field
          v-model="userUpdate.email"
          :label=" $t('email') "
          :rules="[
            v => !!v || $t('validation.emailRequired'),
            v => /.+@.+\..+/.test(v) || $t('validation.emailInvalid')
          ]"
          required
          class="mb-3"
        />
        <v-select
          v-model="userUpdate.role_id"
          :items="roleOptions"
          item-value="value"
          item-title="text"
          :label=" $t('role') "
          :rules="[
            v => !!v || $t('validation.roleRequired')
          ]"
          required
          class="mb-4"
        />
        <div class="sidebar-actions">
          <v-btn color="#9155FD" type="submit" :loading="updateUserLoading" block>
            {{ $t('update') }}
          </v-btn>
        </div>
      </v-form>
    </v-navigation-drawer>

      <v-snackbar
      v-model="snackbar"
      :color="snackbarColor"
      timeout="4000"
      location="top right"
      variant="elevated"
    >
    <span class="snackbar-pre">
      {{ snackbarMessage }}
    </span>
    </v-snackbar>
  </div>

</template>


<script setup>
import HTTP from '@/lib/axios';
import { showError } from '@/utils/errorMessageSwal';
import { defineEmits, defineProps, ref, watch } from 'vue';

const props = defineProps({
  modelValue: Boolean,
  fetchUsers: Function,
  selectedUser: Object,
})
const emit = defineEmits(['update:modelValue'])

const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success') // 'success' ou 'error'

const drawer = ref(props.modelValue)
const updateUserLoading = ref(false)
const addUserForm = ref(null)
const userUpdate = ref({
  id: null,
  name: '',
  email: '',
  role_id: null,
})
const roleOptions = [
  { value: 1, text: 'owner' },
  { value: 2, text: 'admin' },
  { value: 3, text: 'user' },
]
watch(() => props.selectedUser, val => {
  userUpdate.value = {
    id: val.id,
    name: val.name,
    email: val.email,
    role_id: val.role_id,
  }
})

watch(() => props.modelValue, val => { drawer.value = val })
watch(drawer, val => {
  emit('update:modelValue', val)
  if (!val) {
    resetForm()
  }
})

function resetForm() {
  userUpdate.value = {  id: null, name: '', email: '', role_id: '' }
  if (addUserForm.value) addUserForm.value.resetValidation()
}

function showSnackbar(message, color = 'success') {
  snackbarMessage.value = message
  snackbarColor.value = color
  snackbar.value = true
}

async function submitUpdateUser() {
  if (!addUserForm.value) return
  const valid = await addUserForm.value.validate()
  if (!valid.valid) return

  updateUserLoading.value = true
  try {
    const response = await HTTP.post('user/update', userUpdate.value)
    if (response.status === 200 || response.status === 201) {
      props.fetchUsers()
      drawer.value = false
      updateUserLoading.value = false
      showSnackbar(response.data.message, 'success')
    }
  } catch (error) {
    showError(error)
  } finally {
    updateUserLoading.value = false
  }
}


</script>

<style scoped>

.add-user-sidebar {
  padding: 0 0 0 0;
  box-shadow: -2px 0 16px rgba(145, 85, 253, 0.08);
  z-index: 1200;
}
.sidebar-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 24px 8px 24px;
}
.sidebar-title {
  font-size: 1.2rem;
  font-weight: 600;
}
.sidebar-form {
  padding: 24px;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
.sidebar-actions {
  margin-top: 18px;
  display: flex;
  justify-content: flex-end;
}

.menu-action-list .v-list-item-title i {
  color: #9155FD;
  font-size: 1.2em;
}

</style>


  
