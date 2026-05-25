<template>
  <!-- HEADER -->
  <div class="category-header-line">
  
  <!-- TITLE -->
  <h2 class="category-page-title">
    {{ $t('categories') }}
    <span v-if="items.length > 0">({{ items.length }})</span>
  </h2>

  <!-- SEARCH -->
  <v-text-field
    v-model="search"
    :label="$t('search')"
    prepend-inner-icon="ri-search-line"
    density="compact"
    variant="outlined"
    hide-details
    class="search-field"
  />

  <!-- BUTTON -->
  <v-btn color="#9155FD" @click="addCategory">
    <i class="ri-add-line"></i>
    <span class="ml-2">{{ $t('add') }}</span>
  </v-btn>

</div>

  <!-- DRAWERS -->
  <AddCategory v-model="drawerAdd" :fetch-categories="fetchCategories" />
  <EditCategory
    v-model="drawerUpdate"
    :fetch-categories="fetchCategories"
    :selected-category="selectedCategory"
  />

  <!-- MOBILE -->
  <div class="category-list-mobile" :class="`theme-${theme}`">
    <div v-for="item in filteredItems" :key="item.id" class="category-card-mobile">
      
      <div class="category-card-row">
        <span class="category-card-label">{{ $t('name') }} :</span>
        <span class="category-card-value">{{ item.name }}</span>
      </div>

      <div class="category-card-actions">
        <v-btn icon size="small" @click="editItem(item)">
          <i class="ri-pencil-line"></i>
        </v-btn>

        <v-btn icon size="small" @click="deleteItem(item)">
          <i class="ri-delete-bin-5-line"></i>
        </v-btn>
      </div>
    </div>

    <div v-if="!loading && filteredItems.length === 0" class="no-categorys">
      {{ $t('noData') }}
    </div>
  </div>

  <!-- DESKTOP -->
  <v-data-table
    :headers="headers"
    :items="filteredItems"
    class="custom-table category-table-desktop"
  >
    <template #item.name="{ item }">
      <span style="font-weight: bold;">{{ item.name }}</span>
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
              <i class="ri-pencil-line"></i>
              <span class="ml-2">{{ $t('edit') }}</span>
            </v-list-item-title>
          </v-list-item>

          <v-list-item @click="deleteItem(item)">
            <v-list-item-title>
              <i class="ri-delete-bin-5-line"></i>
              <span class="ml-2">{{ $t('delete') }}</span>
            </v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </template>

    <template #no-data>
      <div class="no-categorys" v-if="!loading">
        {{ $t('noData') }}
      </div>
    </template>
  </v-data-table>

  <!-- LOADING -->
  <v-overlay
    :model-value="loading"
    opacity="0.2"
    persistent
    contained
    class="d-flex align-center justify-center"
  >
    <v-progress-circular indeterminate color="primary" size="48" />
  </v-overlay>

  <!-- SNACKBAR -->
  <v-snackbar
    v-model="snackbar"
    :color="snackbarColor"
    timeout="4000"
    location="top right"
  >
    {{ snackbarMessage }}
  </v-snackbar>
</template>

<script setup>
import HTTP from '@/lib/axios'
import Swal from 'sweetalert2'
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { showError } from '@/utils/errorMessageSwal'

import AddCategory from './action/Add.vue'
import EditCategory from './action/Edit.vue'

const { t } = useI18n()

const theme = ref(localStorage.getItem('selected-theme') || 'light')

// 🔍 search
const search = ref('')

const headers = computed(() => [
  { title: t('name'), value: 'name' },
  { title: t('action'), value: 'action' },
])

const items = ref([])
const loading = ref(false)

const drawerAdd = ref(false)
const drawerUpdate = ref(false)

const selectedCategory = ref({
  id: '',
  name: '',
})

const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

// 🔥 FILTER
const filteredItems = computed(() => {
  if (!search.value) return items.value

  return items.value.filter(item =>
    item.name.toLowerCase().includes(search.value.toLowerCase())
  )
})

onMounted(() => {
  window.addEventListener('theme-changed', updateTheme)
  window.addEventListener('storage', updateTheme)
  fetchCategories()
})

onUnmounted(() => {
  window.removeEventListener('theme-changed', updateTheme)
  window.removeEventListener('storage', updateTheme)
})

function updateTheme() {
  theme.value = localStorage.getItem('selected-theme') || 'light'
}

function addCategory() {
  drawerAdd.value = true
}

function editItem(item) {
  selectedCategory.value = item
  drawerUpdate.value = true
}

async function fetchCategories() {
  loading.value = true
  try {
    const response = await HTTP.get('category/get')
    if (response.status === 200) {
      items.value = response.data.categories
    }
  } catch (error) {
    showError(error)
  } finally {
    loading.value = false
  }
}

async function deleteItem(item) {
  const result = await Swal.fire({
    title: 'Supprimer ?',
    text: 'Action irréversible',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#9155FD',
    cancelButtonColor: '#2196F3',
    confirmButtonText: 'Oui',
    cancelButtonText: 'Annuler',
  })

  if (result.isConfirmed) {
    loading.value = true
    try {
      const res = await HTTP.post('category/delete', { id: item.id })
      fetchCategories()
      showSnackbar(res.data.message, 'success')
    } catch (error) {
      showError(error)
    } finally {
      loading.value = false
    }
  }
}

function showSnackbar(msg, color = 'success') {
  snackbarMessage.value = msg
  snackbarColor.value = color
  snackbar.value = true
}
</script>

<style scoped>
.category-table-desktop {
  display: none;
}
.category-list-mobile {
  display: block;
}
@media (min-width: 800px) {
  .category-table-desktop {
    display: block;
  }
  .category-list-mobile {
    display: none;
  }
}
.category-card-mobile {
  background: #232333;
  border-radius: 10px;
  padding: 16px;
  margin-bottom: 16px;
}
.category-card-row {
  display: flex;
  justify-content: space-between;
}
.category-card-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 10px;
}
.no-categorys {
  text-align: center;
  color: #9155FD;
  padding: 20px;
}
.category-header-line {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 20px;
  flex-wrap: wrap; /* مهم للموبايل */
}
/* --------------- recherche ---------------*/
.search-field {
  max-width: 300px;
  flex: 1;
}

.category-page-title {
  white-space: nowrap;
  font-size: 1.4rem;
  font-weight: 600;
}

</style>
