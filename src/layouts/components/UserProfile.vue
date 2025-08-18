<script setup>
import HTTP from '@/lib/axios'
import avatar1 from '@images/avatars/avatar-1.png'
import Swal from 'sweetalert2'
import { ref } from 'vue'
import { useRouter } from 'vue-router'


const router = useRouter()

const user = ref({})
try {
  const userStr = localStorage.getItem('user')
  if (userStr) {
    user.value = JSON.parse(userStr)
  }
} catch (e) {
  user.value = {}
}

// ... existing code ...
const logout = async () => {
  // Détection de la langue de l'utilisateur
  let lang = 'fr'
  try {
    const userStr = localStorage.getItem('user')
    if (userStr) {
      const userObj = JSON.parse(userStr)
      lang = userObj.langue || 'fr'
    }
  } catch (e) {
    lang = 'fr'
  }

  // Textes multilingues
  const texts = {
    fr: {
      title: "Êtes-vous sûr de vouloir vous déconnecter ?",
      text: "Vous serez redirigé vers la page de connexion.",
      confirm: "Oui, se déconnecter",
      cancel: "Annuler"
    },
    en: {
      title: "Are you sure you want to log out?",
      text: "You will be redirected to the login page.",
      confirm: "Yes, log out",
      cancel: "Cancel"
    }
  }
  const t = texts[lang] || texts.fr

  // Afficher une confirmation avant de déconnecter
  if (typeof Swal !== 'undefined') {
    const result = await Swal.fire({
      title: t.title,
      text: t.text,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#9155FD', 
      cancelButtonColor: '#2196F3',
      confirmButtonText: `<span style="color: #fff">${t.confirm }</span>`,
      cancelButtonText: `<span style="color: #fff">${t.cancel}</span>`
    });
    if (!result.isConfirmed) return;
  } else {
    // fallback si Swal n'est pas disponible
    if (!window.confirm(t.title)) return;
  }

  try {
    const response = await HTTP.post('/logout')
    if (response.status === 200) {
      localStorage.removeItem('authToken')
      localStorage.removeItem('user')
      router.push('/login')
    }
  } catch (error) {
    console.error(error)
  }
}
// ... existing code ...
</script>

<template>
  <VBadge
    dot
    location="bottom right"
    offset-x="3"
    offset-y="3"
    color="success"
    bordered
  >
    <VAvatar
      class="cursor-pointer"
      color="primary"
      variant="tonal"
    >
      <VImg :src="avatar1" />

      <!-- SECTION Menu -->
      <VMenu
        activator="parent"
        width="230"
        location="bottom end"
        offset="14px"
      >
        <VList>
          <!-- 👉 User Avatar & Name -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge
                  dot
                  location="bottom right"
                  offset-x="3"
                  offset-y="3"
                  color="success"
                >
                  <VAvatar
                    color="primary"
                    variant="tonal"
                  >
                    <VImg :src="avatar1" />
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-semibold">
              <!-- Affichage du nom de l'utilisateur -->
              {{ user.name }}
            </VListItemTitle>
            <VListItemSubtitle>
              <!-- Affichage du rôle si disponible -->
              {{ user.role || '' }}
            </VListItemSubtitle>
          </VListItem>
          <VDivider class="my-2" />

          <!-- 👉 Profile -->
          <VListItem link>
            <template #prepend>
              <VIcon
                class="me-2"
                icon="ri-user-line"
                size="22"
              />
            </template>

            <VListItemTitle>Profile</VListItemTitle>
          </VListItem>

          <!-- 👉 Settings -->
          <VListItem link>
            <template #prepend>
              <VIcon
                class="me-2"
                icon="ri-settings-4-line"
                size="22"
              />
            </template>

            <VListItemTitle>Settings</VListItemTitle>
          </VListItem>

          <!-- Divider -->
          <VDivider class="my-2" />

          <!-- 👉 Logout -->
          <VListItem @click="logout" style="cursor:pointer;">
            <template #prepend>
              <VIcon
                class="me-2"
                icon="ri-logout-box-r-line"
                size="22"
              />
            </template>

            <VListItemTitle>Logout</VListItemTitle>
          </VListItem>
        </VList>
      </VMenu>
      <!-- !SECTION -->
    </VAvatar>
  </VBadge>
</template>
