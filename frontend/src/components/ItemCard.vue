<template>
  <div @click="handleClick" class="group h-full overflow-hidden rounded-lg border border-border bg-white transition-all hover:shadow-md cursor-pointer" :class="{ 'border-2 border-primary': item.featured }">
    <div class="relative aspect-video overflow-hidden">
      <img :src="item.image" :alt="item.title" class="h-full w-full object-cover transition-transform group-hover:scale-105">
      <div class="absolute left-2 top-2 z-10 rounded-md px-2 py-1 text-xs font-semibold uppercase"
           :class="item.type === 'lost' ? 'bg-red-100 text-red-500' : 'bg-green-100 text-green-600'">
        {{ item.type }}
      </div>
      <div v-if="item.featured" class="absolute right-2 top-2 z-10 rounded-md bg-purple-100 px-2 py-1 text-xs font-semibold text-primary-dark">
        Featured
      </div>
    </div>
    <div class="p-4">
      <div class="flex items-start justify-between">
        <h3 class="line-clamp-1 font-semibold">{{ item.title }}</h3>
        <span class="ml-2 shrink-0 rounded-full bg-gray-100 px-2 py-0.5 text-xs text-muted-foreground">{{ item.category?.name || 'Uncategorized' }}</span>
      </div>
      <p class="line-clamp-2 mt-2 text-sm text-muted-foreground">{{ item.description }}</p>
      <!-- Status badge if showStatus is true -->
      <div v-if="showStatus" class="mt-2">
        <span 
          :class="[`inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium`, getStatusClass(item.status)]"
        >
          {{ item.status.charAt(0).toUpperCase() + item.status.slice(1) }}
        </span>
      </div>
      
      <div class="mt-4 flex items-center justify-between text-xs text-muted-foreground">
        <div class="flex items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
            <circle cx="12" cy="10" r="3"></circle>
          </svg>
          <span>{{ item.location }}</span>
        </div>
        <div class="flex items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
            <line x1="16" x2="16" y1="2" y2="6"></line>
            <line x1="8" x2="8" y1="2" y2="6"></line>
            <line x1="3" x2="21" y1="10" y2="10"></line>
          </svg>
          <span>{{ formatDate(item.type === 'lost' ? item.lost_date : item.found_date) }}</span>
        </div>
        <div class="flex items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
            <circle cx="12" cy="12" r="3"></circle>
          </svg>
          <span>{{ item.views }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { format, formatDistanceToNow } from 'date-fns';

const props = defineProps({
  item: {
    type: Object,
    required: true
  },
  showStatus: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['click']);

const handleClick = () => {
  emit('click', props.item);
};

const getStatusClass = (status) => {
  const classMap = {
    'active': 'bg-green-100 text-green-800',
    'archived': 'bg-gray-100 text-gray-800',
    'reported': 'bg-yellow-100 text-yellow-800',
    'deleted': 'bg-red-100 text-red-800'
  };
  return classMap[status] || 'bg-gray-100 text-gray-800';
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  
  try {
    const date = new Date(dateString);
    
    // Check if date is valid
    if (isNaN(date.getTime())) {
      return 'Invalid date';
    }
    
    // If date is less than 7 days ago, show relative time
    const now = new Date();
    const diffInDays = Math.floor((now - date) / (1000 * 60 * 60 * 24));
    
    if (diffInDays < 7) {
      return formatDistanceToNow(date, { addSuffix: true });
    }
    
    // Otherwise show formatted date
    return format(date, 'MMM d, yyyy');
  } catch (error) {
    console.error('Error formatting date:', error);
    return 'Error';
  }
};
</script>
