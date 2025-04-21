<template>
  <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 transition-all hover:shadow-lg">
    <div class="relative pb-[75%] bg-gray-100">
      <img 
        v-if="item.image" 
        :src="item.image" 
        :alt="item.title" 
        class="absolute inset-0 w-full h-full object-cover"
      />
      <div v-else class="absolute inset-0 flex items-center justify-center text-gray-400">
        <ImageIcon class="h-12 w-12" />
      </div>
    </div>
    
    <div class="p-4">
      <h3 class="font-semibold text-lg mb-1 truncate">{{ item.title }}</h3>
      
      <div class="flex items-center text-sm text-gray-500 mb-2">
        <MapPin class="h-4 w-4 mr-1" />
        <span>{{ item.location }}</span>
      </div>
      
      <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ item.description }}</p>
      
      <div class="flex items-center justify-between">
        <span class="text-xs px-2 py-1 rounded-full" :class="categoryClass">
          {{ item.category }}
        </span>
        
        <span class="text-xs text-gray-500">
          {{ formatDate(item.date) }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { MapPin, Image as ImageIcon } from 'lucide-vue-next'

const props = defineProps({
  item: {
    type: Object,
    required: true
  }
})

const categoryClass = computed(() => {
  const categories = {
    'lost': 'bg-red-100 text-red-800',
    'found': 'bg-green-100 text-green-800',
    'default': 'bg-gray-100 text-gray-800'
  }
  
  return categories[props.item.category.toLowerCase()] || categories.default
})

const formatDate = (dateString) => {
  try {
    // Check if dateString is a relative time string like "2 hours ago"
    if (typeof dateString === 'string' && dateString.includes('ago')) {
      return dateString; // Just return the relative string as is
    }
    
    // Try to parse the date
    const date = new Date(dateString);
    
    // Check if date is valid
    if (isNaN(date.getTime())) {
      return dateString; // Return original string if parsing failed
    }
    
    // Format the date if valid
    return new Intl.DateTimeFormat('fr-FR', { 
      day: 'numeric', 
      month: 'short'
    }).format(date);
  } catch (error) {
    console.error('Error formatting date:', error);
    return dateString; // Return the original string in case of any error
  }
}
</script>
