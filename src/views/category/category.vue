<template>
    <div class="user-page-header-responsive">
        <h2 class="user-page-title">
        {{ $t('category') }} 
        <span>({{ items.length }})</span>
        </h2>
        <v-btn color="#9155FD" class="add-user-btn-responsive" @click="addUser">
        <i class="ri-add-line"></i>
        <span class="add-user-btn-text">{{ $t('add') }}</span>
        </v-btn>
    </div>



</template>
  
<script setup>

const items = ref([])


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
