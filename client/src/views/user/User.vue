<template>
  <div class="user-page-header-responsive">
    <h2 class="user-page-title">
      {{ $t('users') }} 
      <span v-if="items.length > 0">({{ items.length }})</span>
    </h2>
    <v-btn color="#9155FD" class="add-user-btn-responsive" @click="addUser">
      <i class="ri-add-line"></i>
      <span class="add-user-btn-text">{{ $t('add') }}</span>
    </v-btn>
  </div>


  <Add v-model="drawerAdd" :fetch-users="fetchUsers" />
  <Update v-model="drawerUpdate" :fetch-users="fetchUsers" :selected-user="selectedUser" />
  <!-- Mobile version -->
  <div class="user-list-mobile" :class="`theme-${theme}`">
    <div v-for="item in items" :key="item.email" class="user-card-mobile">
      <div class="user-card-row">
        <span class="user-card-label">{{ $t('fullName') }} :</span>
        <span class="user-card-value">{{ item.name }}</span>
      </div>
      <div class="user-card-row">
        <span class="user-card-label">{{ $t('email') }} :</span>
        <a class="user-card-value" :href="`mailto:${item.email}`">{{ item.email }}</a>
      </div>
      <div class="user-card-row">
        <span class="user-card-label">{{ $t('role') }} :</span>
        <span class="user-card-value">{{ item.role }}</span>
      </div>
      <div class="user-card-actions">
        <v-btn icon size="small" @click="editItem(item)">
          <i class="ri-add-line"></i>
        </v-btn>
        <v-btn icon size="small" @click="deleteItem(item)">
          <i class="ri-delete-bin-5-line"></i>
        </v-btn>
        <v-btn icon size="small" @click="regenerateItem(item)">
          <i class="ri-refresh-line"></i>
        </v-btn>
      </div>
    </div>
    <div v-if="!loading && items.length === 0" class="no-users">Aucun utilisateur n’a été inséré</div>
  </div>

  <!-- Desktop version -->
  <v-data-table
    :headers="headers"
    :items="items"
    mobile-breakpoint="600"
    class="custom-table user-table-desktop"
  >
    <!-- ... existing slots ... -->
    <template #no-data>
      <div class="no-users" v-if="!loading">
        {{ $t('noData') }} 
      </div>
    </template>
    <template #item.name="{ item }">
      <span style=" font-weight: bold;">{{ item.name }}</span>
    </template>
    <template #item.role="{ item }">
      <span>{{ item.role }}</span>
    </template>
    <template #item.email="{ item }">
      <a style="color: inherit;" :href="`mailto:${item.email}`">{{ item.email }}</a>
    </template>
    <template #item.action="{ item }">
      <v-menu>
        <template #activator="{ props }">
          <span v-bind="props" style="cursor:pointer;">
            <i class="ri-more-2-fill" style="font-size: 1.4rem; color: #9155FD;"></i>
          </span>
        </template>
        <v-list class="menu-action-list">
          <v-list-item @click="editItem(item)">
            <v-list-item-title>
              <i class="ri-pencil-line"></i> <span class="ml-2">{{ $t('edit') }}</span>
            </v-list-item-title>
          </v-list-item>
          <v-list-item @click="deleteItem(item)">
            <v-list-item-title>
              <i class="ri-delete-bin-5-line"></i> <span class="ml-2">{{ $t('delete') }}</span>
            </v-list-item-title>
          </v-list-item>
          <v-list-item @click="regenerateItem(item)">
            <v-list-item-title>
              <i class="ri-refresh-line">
              </i> 
              <span class="ml-2">{{ $t('regenerate') }}</span>
            </v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </template>
  </v-data-table>

  <v-overlay
    :model-value="loading"
    opacity="0.2"
    persistent
    contained
    class="d-flex align-center justify-center"
  >
    <v-progress-circular
      indeterminate
      color="primary"
      size="48"
    />
  </v-overlay>

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
</template>

<script setup>
import HTTP from '@/lib/axios';
import { showError } from '@/utils/errorMessageSwal';
import Swal from 'sweetalert2';
import { computed, onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import Add from './action/Add.vue'; // ← AJOUT
import Update from './action/Edit.vue';



const theme = ref(localStorage.getItem('selected-theme') || 'light')
const { t } = useI18n()

const headers = computed(() => [
  { title: t('fullName'), value: 'name' },
  { title: t('email'), value: 'email' },
  { title: t('role'), value: 'role' },
  { title: t('action'), value: 'action' },
])
const items = ref([])
const loading = ref(false)

const drawerAdd = ref(false)
const selectedUser = ref({
  id: '',
  name: '',
  email: '',
  role: '',
  role_id: '',
})

const drawerUpdate = ref(false)
const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success') 

onMounted(() => {
  window.addEventListener('theme-changed', updateTheme)
  window.addEventListener('storage', updateTheme)
  fetchUsers()
})

onUnmounted(() => {
  window.removeEventListener('theme-changed', updateTheme)
  window.removeEventListener('storage', updateTheme)
})

function updateTheme() {
  theme.value = localStorage.getItem('selected-theme') || 'light'
}

function addUser() {
  drawerAdd.value = true
}

async function deleteItem(item) {
  const result = await Swal.fire({
    title: t('confirmDeleteTitle') || 'Êtes-vous sûr ?',
    text: t('confirmDeleteText') || "Cette action est irréversible !",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#9155FD', 
    cancelButtonColor: '#2196F3',  
    confirmButtonText: `<span style="color: #fff">${t('yesDelete') || 'Oui, supprimer !'}</span>`,
    cancelButtonText: `<span style="color: #fff">${t('cancel') || 'Annuler'}</span>`,
   
  });

  if (result.isConfirmed) {
    loading.value = true;
    try {
      const response = await HTTP.post('user/delete', { id: item.id });
      if (response.status === 200 || response.status === 201) {
        await fetchUsers();
        showSnackbar(response.data.message, 'success')
      } else {
        showSnackbar(response.data.message, 'error')
      }
    } catch (error) {
      showError(error)
    } finally {
      loading.value = false;
    }
  }
}

function showSnackbar(message, color = 'success') {
  snackbarMessage.value = message
  snackbarColor.value = color
  snackbar.value = true
}

async function fetchUsers() {
  loading.value = true
  try {
    const response = await HTTP.get('user/get')
    if (response.status === 200) {
      items.value = response.data.users
    }
  } catch (error) {
    console.error('Erreur lors de la récupération des utilisateurs:', error)
    showError(error)
  } finally {
    loading.value = false
  }
}

function editItem(item) {
  drawerUpdate.value = true
  selectedUser.value = item
}

function regenerateItem(item) {
  alert('Régénérer: ' + item.name)
}
</script>

<style scoped>
.user-page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 18px;
}
.user-page-header h2 {
  font-size: 1.8rem;
  font-weight: 600;
  margin: 0;
}
.add-user-btn {
  color: #fff !important;
  font-weight: 500;
  text-transform: none;
  box-shadow: 0 2px 8px rgba(145, 85, 253, 0.10);
}
.no-users {
  text-align: center;
  color: #9155FD;
  font-size: 1.1rem;
  padding: 32px 0;
  font-weight: 500;
}

/* Sidebar styles */
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

.user-table-desktop {
  display: none;
}
.user-list-mobile {
  display: block;
}
@media (min-width: 800px) {
  .user-table-desktop {
    display: block;
  }
  .user-list-mobile {
    display: none;
  }
}

/* Style pour la liste mobile */
.user-card-mobile {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(145, 85, 253, 0.08);
  margin-bottom: 16px;
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.user-card-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.user-card-label {
  font-weight: 700;
  color: #000000;
  min-width: 70px;
}
.user-card-value {
  font-weight: 400;
  color: #333;
  word-break: break-all;
}
.user-card-actions {
  display: flex;
  gap: 8px;
  margin-top: 8px;
  justify-content: flex-end;
}

 .user-page-header-responsive {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-bottom: 24px;
    gap: 12px;
  }
  .user-page-title {
    font-size: 1.5rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .add-user-btn-responsive {
    min-width: 0;
    padding: 0 12px;
    font-size: 1rem;
    display: flex;
    align-items: center;
    height: 40px;
  }
  .add-user-btn-text {
    margin-left: 5px;
    white-space: nowrap;
  }
  @media (max-width: 800px) {
    .user-page-header-responsive {
      align-items: center;
      gap: 8px;
    }
    .user-page-title {
      font-size: 1.15rem;
    }
    .add-user-btn-responsive {
      font-size: 0.98rem;
      height: 38px;
    }
    .add-user-btn-text {
      font-size: 0.98rem;
    }
  }


.user-list-mobile.theme-dark .user-card-mobile {
  background: #232333;
  color: #fff;
  box-shadow: 0 2px 8px rgba(145, 85, 253, 0.15);
}
.user-list-mobile.theme-dark .user-card-label {
  color: #b39ddb;
}
.user-list-mobile.theme-dark .user-card-value {
  color: #fff;
}
.user-list-mobile.theme-dark .user-card-actions .v-btn {
  background: #2c2c40;
}
.user-list-mobile.theme-dark .user-card-actions .v-btn i {
  color: #ffffff;
}
</style>
