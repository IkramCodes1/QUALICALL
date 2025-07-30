<script setup>
import logo from '@images/logo.svg?raw'
import authV1MaskDark from '@images/pages/auth-v1-mask-dark.png'
import authV1MaskLight from '@images/pages/auth-v1-mask-light.png'
import authV1Tree2 from '@images/pages/auth-v1-tree-2.png'
import authV1Tree from '@images/pages/auth-v1-tree.png'
import { useTheme } from 'vuetify'

import HTTP from '@/lib/axios'
import { computed, ref } from 'vue'
// ...autres imports

const form = ref({
  email: '',
  password: '',
})

const isPasswordVisible = ref(false)

const errorMessage = ref('')

const login = async () => {
  errorMessage.value = ''

  if (!form.value.email || !form.value.password) {
    errorMessage.value = 'Veuillez remplir tous les champs.'
    return
  }

  try {
    const response = await HTTP.post('/login', {
      email: form.value.email,
      password: form.value.password,
    })
    const token = response.data
    localStorage.setItem('authToken', token)
    window.location.href = '/dashboard'
  } catch (error) {
    if (error.response && error.response.status === 401) {
      errorMessage.value = 'Email ou mot de passe incorrect.'
    } else if (error.response && error.response.status === 422) {
      errorMessage.value = 'Format de l’email ou du mot de passe invalide.'
    } else {
      errorMessage.value = 'Erreur de connexion. Veuillez réessayer.'
    }
  }
}



const vuetifyTheme = useTheme()

const authThemeMask = computed(() => {
  return vuetifyTheme.global.name.value === 'light' ? authV1MaskLight : authV1MaskDark
})

</script>

<template>
  <!-- eslint-disable vue/no-v-html -->

  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <VCard
      class="auth-card pa-4 pt-7"
      max-width="448"
    >
      <VCardItem class="justify-center">
        <RouterLink
          to="/"
          class="d-flex align-center gap-3"
        >
          <!-- eslint-disable vue/no-v-html -->
          <div
            class="d-flex"
            v-html="logo"
          />
          <h2 class="font-weight-medium text-2xl text-uppercase">
            Materio
          </h2>
        </RouterLink>
      </VCardItem>

      <VCardText class="pt-2">
        <h4 class="text-h4 mb-1">
          Bienvenue sur Materio ! 👋🏻
        </h4>
        <p class="mb-0">
          Veuillez vous connecter à votre compte pour commencer l'aventure
        </p>
      </VCardText>

      <VCardText>
        <div v-if="errorMessage" class="text-error mb-2" style="color: red;">
          {{ errorMessage }}
        </div>
        <VForm @submit.prevent="login">
          <VRow>
            <!-- email -->
            <VCol cols="12">
              <VTextField
                v-model="form.email"
                label="Email"
                type="email"
              />
            </VCol>

            <!-- password -->
            <VCol cols="12">
              <VTextField
                v-model="form.password"
                label="Mot de passe"
                placeholder="············"
                :type="isPasswordVisible ? 'text' : 'password'"
                autocomplete="password"
                :append-inner-icon="isPasswordVisible ? 'ri-eye-off-line' : 'ri-eye-line'"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
              />

              <!-- remember me checkbox -->
              <div class="d-flex align-center justify-space-between flex-wrap my-6">
              

                <div class="d-flex justify-end" style="width: 100%;">
                  <a
                    class="text-primary"
                    href="javascript:void(0)"
                  >
                    Mot de passe oublié ?
                  </a>
                </div>
              </div>

              <!-- login button -->
              <VBtn
                block
                type="submit"
              >
                Connecter
              </VBtn>
            </VCol>

           
          </VRow>
        </VForm>
      </VCardText>
    </VCard>

    <VImg
      class="auth-footer-start-tree d-none d-md-block"
      :src="authV1Tree"
      :width="250"
    />

    <VImg
      :src="authV1Tree2"
      class="auth-footer-end-tree d-none d-md-block"
      :width="350"
    />

    <!-- bg img -->
    <VImg
      class="auth-footer-mask d-none d-md-block"
      :src="authThemeMask"
    />
  </div>
</template>

<style lang="scss">
@use "@core/scss/template/pages/page-auth";
</style>
