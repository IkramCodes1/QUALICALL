<script setup>
import logo from '@images/logo.svg?raw'
import authV1MaskDark from '@images/pages/auth-v1-mask-dark.png'
import authV1MaskLight from '@images/pages/auth-v1-mask-light.png'
import authV1Tree2 from '@images/pages/auth-v1-tree-2.png'
import authV1Tree from '@images/pages/auth-v1-tree.png'
import { useTheme } from 'vuetify' // to detect light/dark mode

import HTTP from '@/lib/axios' // Instance Axios pour faire des requêtes API (login, user…)
import { showError } from '@/utils/errorMessageSwal' // SweetAlert2 : JavaScript library for nice alert popups
import { computed, ref } from 'vue' // gérer la réactivité et les valeurs calculées dans le composant
import { useRouter } from 'vue-router'

// Gestion des traductions
import { useI18n } from 'vue-i18n'
const { locale, t } = useI18n()
// t() → traduire un texte
// locale → langue actuelle



const router = useRouter()

const form = ref({
  email: '',
  password: '',
})

const isPasswordVisible = ref(false)

const isLoading = ref(false) // ex: Désactiver un bouton pendant le chargement pour éviter plusieurs clics.
const errorMessage = ref('')

const login = async () => { // Fonction exécutée quand on clique sur Login.
  if (!form.value.email || !form.value.password) {
    errorMessage.value = t('validation.fillAllFields')
    return
  }

  isLoading.value = true
  try {
    const response = await HTTP.post('/login', { // Appel API login
      email: form.value.email,
      password: form.value.password, // Envoie email + password au backend
    })
    if (response.status === 200 || response.status === 201) {
      const token = response.data
      localStorage.setItem('authToken', token) // sert à récupérer le jeton d’authentification (token) envoyé par ton serveur après la connexion, puis à le stocker dans le navigateur pour que l’utilisateur reste connecté.
      const responseUser = await HTTP.get('/user')
      if (responseUser.status === 200 || responseUser.status === 201) {
        localStorage.setItem('user', JSON.stringify(responseUser.data.user)) // sert à enregistrer les informations de l’utilisateur dans le navigateur.
        let user = {}

        try {
          user = JSON.parse(localStorage.getItem('user') || '{}')
        } catch (e) {
          localStorage.removeItem('user')
        }
        locale.value = user.langue || 'en' // si user.langue existe si non en mt englais
      }
      router.push('/conversation') // Redirection vers dashboard
    }
  } catch (error) {
    let icon = 'error'
    let title = t('error')

    if (error?.response) {
      const status = error.response.status

      // Mot de passe incorrect
      if (status === 401) {
        icon = 'warning'
        title = t('incorrectPasswordTitle')
      }
      // Utilisateur non trouvé
      else if (status === 404) {
        icon = 'warning'
        title = t('userNotFoundTitle')
      }
      // Compte bloqué
      else if (status === 403) {
        icon = 'error'
        title = t('accountBlockedTitle')
      }
    }

    showError(error, icon, title)
  } finally {
    isLoading.value = false
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
    <VCard class="auth-card pa-4 pt-7" max-width="448">
      <VCardItem class="justify-center">
        <RouterLink to="/" class="d-flex align-center gap-3">
          <!-- eslint-disable vue/no-v-html -->
          <div class="d-flex" v-html="logo" />
          <h2 class="font-weight-medium text-2xl text-uppercase">
            QUALICALL
          </h2>
        </RouterLink>
      </VCardItem>

      <VCardText class="pt-2">
        <h4 class="text-h4 mb-1">
          {{ t('welcome') }}
        </h4>
        <p class="mb-0">
          {{ t('loginMessage') }}
        </p>
      </VCardText>

      <VCardText>
        <!-- Error message -->
        <div v-if="errorMessage" class="mb-4">
          <VAlert type="error" variant="tonal" border="start" prominent class="pa-2">
            {{ errorMessage }}
          </VAlert>
        </div>
        <VForm @submit.prevent="login">
          <VRow>
            <!-- email -->
            <VCol cols="12">
              <VTextField v-model="form.email" :label="t('email')" type="email" :disabled="isLoading" />
            </VCol>

            <!-- password -->
            <VCol cols="12">
              <VTextField v-model="form.password" :label="t('password')" placeholder="············"
                :type="isPasswordVisible ? 'text' : 'password'" autocomplete="password"
                :append-inner-icon="isPasswordVisible ? 'ri-eye-off-line' : 'ri-eye-line'"
                @click:append-inner="isPasswordVisible = !isPasswordVisible" :disabled="isLoading" />

              <!-- remember me checkbox -->
              <div class="d-flex align-center justify-space-between flex-wrap my-6">
                <div class="d-flex justify-space-between" style="width: 100%;">
                  <!-- Lien mot de passe oublié -->
                  <a class="text-primary" href="javascript:void(0)">
                    {{ t('forgotPassword') }}
                  </a>

                  <!-- Lien créer un compte -->
                  <RouterLink class="text-primary" to="/register">
                    {{ t('registerNow') }}
                  </RouterLink>
                </div>
              </div>


              <!-- login button -->
              <VBtn block type="submit" :loading="isLoading" :disabled="isLoading">
                {{ t('login') }}
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>

    <VImg class="auth-footer-start-tree d-none d-md-block" :src="authV1Tree" :width="250" />

    <VImg :src="authV1Tree2" class="auth-footer-end-tree d-none d-md-block" :width="350" />

    <!-- bg img -->
    <VImg class="auth-footer-mask d-none d-md-block" :src="authThemeMask" />
  </div>
</template>

<style lang="scss">
@use "@core/scss/template/pages/page-auth";
</style>
