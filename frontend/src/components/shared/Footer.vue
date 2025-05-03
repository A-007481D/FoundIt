<template>
  <footer class="border-t py-4 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <p class="text-sm text-gray-500">
          &copy; {{ currentYear }} {{ t('app.name') }}! {{ t('app.copyright', { year: currentYear }) }}
        </p>
        <div class="flex items-center gap-4 text-sm text-gray-500">
          <router-link :to="t('routes.terms')" class="hover:text-gray-700 transition-colors">
            {{ t('app.terms') }}
          </router-link>
          <router-link :to="t('routes.privacy')" class="hover:text-gray-700 transition-colors">
            {{ t('app.privacy') }}
          </router-link>
          <router-link :to="t('routes.contact')" class="hover:text-gray-700 transition-colors">
            {{ t('app.contact') }}
          </router-link>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const currentYear = computed(() => new Date().getFullYear())
</script>
