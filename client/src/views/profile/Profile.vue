<template>
    <v-container max-width="800" class="py-8">
  
      <v-card rounded="xl" elevation="4">
  
        <!-- HEADER -->
        <v-card-title class="d-flex justify-space-between align-center">
          <div class="text-h6 font-weight-bold">Mon Compte</div>
  
          <v-btn 
            color="primary" 
            :loading="loading"
            @click="handleSave"
          >
            Sauvegarder
          </v-btn>
        </v-card-title>
  
        <v-divider></v-divider>
  
        <!-- TABS -->
        <v-tabs v-model="tab" color="primary">
          <v-tab value="profile">Profil</v-tab>
          <v-tab value="security">Sécurité</v-tab>
        </v-tabs>
  
        <v-window v-model="tab">
  
          <!-- PROFILE TAB -->
          <v-window-item value="profile">
            <v-card-text>
  
              <!-- AVATAR -->
              <div class="text-center mb-6">
                <v-avatar size="90">
                  <v-img :src="previewImage || defaultAvatar" />
                </v-avatar>
  
                <v-file-input
                  class="mt-3"
                  density="compact"
                  accept="image/*"
                  label="Changer photo"
                  @change="onImageChange"
                />
              </div>
  
              <v-row>
  
                <v-col cols="12">
                  <v-text-field
                    v-model="form.username"
                    label="Username"
                    :rules="[rules.required]"
                    prepend-inner-icon="mdi-account"
                    variant="outlined"
                  />
                </v-col>
  
                <v-col cols="12">
                  <v-text-field
                    v-model="form.email"
                    label="Email"
                    :rules="[rules.required, rules.email]"
                    prepend-inner-icon="mdi-email"
                    variant="outlined"
                  />
                </v-col>
  
              </v-row>
            </v-card-text>
          </v-window-item>
  
          <!-- SECURITY TAB -->
          <v-window-item value="security">
            <v-card-text>
  
              <v-row>
  
                <v-col cols="12">
                  <v-text-field
                    v-model="form.password"
                    label="Nouveau mot de passe"
                    type="password"
                    :rules="[rules.password]"
                    prepend-inner-icon="mdi-lock"
                    variant="outlined"
                  />
                </v-col>
  
                <v-col cols="12">
                  <v-text-field
                    v-model="form.confirmPassword"
                    label="Confirmer mot de passe"
                    type="password"
                    :rules="[rules.confirmPassword]"
                    prepend-inner-icon="mdi-lock-check"
                    variant="outlined"
                  />
                </v-col>
  
              </v-row>
  
            </v-card-text>
          </v-window-item>
  
        </v-window>
  
      </v-card>
    </v-container>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import axios from 'axios'
  import Swal from 'sweetalert2'
  import { showError } from '@/utils/errorMessageSwal'
  
  const tab = ref('profile')
  const loading = ref(false)
  
  const defaultAvatar = 'https://cdn-icons-png.flaticon.com/512/149/149071.png'
  const previewImage = ref(null)
  
  const form = ref({
    username: '',
    email: '',
    bio: '',
    password: '',
    confirmPassword: '',
  })
  
  const rules = {
    required: v => !!v || 'Champ requis',
    email: v => /.+@.+\..+/.test(v) || 'Email invalide',
    password: v => !v || v.length >= 6 || 'Min 6 caractères',
    confirmPassword: v =>
      v === form.value.password || 'Passwords non identiques',
  }
  
  const onImageChange = (file) => {
    if (file) {
      previewImage.value = URL.createObjectURL(file)
    }
  }
  
  const handleSave = async () => {
    try {
      loading.value = true
  
      if (form.value.password !== form.value.confirmPassword) {
        throw new Error('Passwords non identiques')
      }
  
      await axios.put('/api/profile', form.value)
  
      Swal.fire({
        icon: 'success',
        title: 'Succès',
        text: 'Profil mis à jour avec succès',
        timer: 2000,
        showConfirmButton: false,
      })
  
    } catch (error) {
      showError(error)
    } finally {
      loading.value = false
    }
  }
  </script>
  
  <style scoped>
  .v-card {
    backdrop-filter: blur(10px);
  }
  </style>
