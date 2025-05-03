<template>
  <div class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-hidden">
    <div class="p-4 pb-3 border-b">
      <div class="flex items-center justify-between">
        <div class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold" :class="statusClass">
          {{ statusLabel }}
        </div>
        <div class="flex items-center gap-2">
          <span class="text-sm font-medium" :class="matchScoreClass">
            {{ match.match_score }}% Match
          </span>
          <div class="h-2 w-14 rounded-full bg-gray-200 overflow-hidden">
            <div class="h-full rounded-full" :class="matchScoreClass" :style="{ width: match.match_score + '%' }">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
      <!-- Lost Item -->
      <div class="p-4 border-b md:border-b-0 md:border-r">
        <div class="flex items-center gap-2 mb-3">
          <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold bg-red-500/10 text-red-500 border-red-500/20">
            Lost
          </div>
          <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold border-input">
            {{ match.lost_item.category }}
          </div>
        </div>

        <h3 class="font-semibold text-base mb-1">{{ match.lost_item.title }}</h3>

        <div class="flex items-center gap-1 text-xs text-muted-foreground mb-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
            <circle cx="12" cy="10" r="3"></circle>
          </svg>
          <span>{{ match.lost_item.location }}</span>
        </div>

        <p class="text-sm line-clamp-2 text-muted-foreground mb-3">{{ match.lost_item.description }}</p>

        <div class="text-xs text-muted-foreground mb-3">
          {{ formatDate(match.lost_item.created_at) }}
        </div>

        <div class="flex items-center gap-2">
          <div class="relative flex h-6 w-6 shrink-0 overflow-hidden rounded-full">
            <img v-if="match.lost_item.user.avatar" :src="match.lost_item.user.avatar" :alt="match.lost_item.user.name" class="aspect-square h-full w-full">
            <div v-else class="flex h-full w-full items-center justify-center rounded-full bg-muted">
              {{ match.lost_item.user.initials }}
            </div>
          </div>
          <span class="text-xs">{{ match.lost_item.user.name }}</span>
        </div>
      </div>

      <!-- Found Item -->
      <div class="p-4">
        <div class="flex items-center gap-2 mb-3">
          <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold bg-secondary/10 text-secondary border-secondary/20">
            Found
          </div>
          <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold border-input">
            {{ match.found_item.category }}
          </div>
        </div>

        <h3 class="font-semibold text-base mb-1">{{ match.found_item.title }}</h3>

        <div class="flex items-center gap-1 text-xs text-muted-foreground mb-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
            <circle cx="12" cy="10" r="3"></circle>
          </svg>
          <span>{{ match.found_item.location }}</span>
        </div>

        <p class="text-sm line-clamp-2 text-muted-foreground mb-3">{{ match.found_item.description }}</p>

        <div class="text-xs text-muted-foreground mb-3">
          {{ formatDate(match.found_item.created_at) }}
        </div>

        <div class="flex items-center gap-2">
          <div class="relative flex h-6 w-6 shrink-0 overflow-hidden rounded-full">
            <img v-if="match.found_item.user.avatar" :src="match.found_item.user.avatar" :alt="match.found_item.user.name" class="aspect-square h-full w-full">
            <div v-else class="flex h-full w-full items-center justify-center rounded-full bg-muted">
              {{ match.found_item.user.initials }}
            </div>
          </div>
          <span class="text-xs">{{ match.found_item.user.name }}</span>
        </div>
      </div>
    </div>

    <!-- Matching attributes -->
    <div class="px-4 py-3 bg-muted/50">
      <div class="flex flex-wrap gap-2">
        <span class="text-xs text-muted-foreground">Matching Attributes:</span>
        <div v-for="(attr, idx) in match.matching_attributes" :key="idx"
             class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold bg-primary/10 text-primary border-primary/20">
          {{ attr }}
        </div>
      </div>
    </div>

    <div class="flex justify-between p-4 border-t">
      <div class="flex gap-2">
        <button class="inline-flex items-center justify-center rounded-md border border-input bg-background px-3 py-2 text-xs font-medium h-8 shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
                @click="viewLostItem(match)">
          <span>Details</span>
        </button>
        <button class="inline-flex items-center justify-center rounded-md border border-input bg-background px-3 py-2 text-xs font-medium h-8 shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 gap-1"
                @click="viewFoundItem(match)">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
            <path d="M20 6 9 17l-5-5"></path>
          </svg>
          <span>Contact</span>
        </button>
      </div>

      <div class="flex gap-2">
        <template v-if="match.status === 'new'">
          <button class="inline-flex items-center justify-center rounded-md border border-input bg-background w-8 h-8 p-0 shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
                  @click="updateMatchStatus(match, 'rejected')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-red-500">
              <path d="M18 6 6 18"></path>
              <path d="m6 6 12 12"></path>
            </svg>
          </button>
          <button class="inline-flex items-center justify-center rounded-md border border-input bg-background w-8 h-8 p-0 shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
                  @click="updateMatchStatus(match, 'in-progress')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-secondary">
              <path d="M20 6 9 17l-5-5"></path>
            </svg>
          </button>
        </template>
        <button v-if="match.status === 'in-progress'" class="inline-flex items-center justify-center rounded-md border border-input bg-background px-3 py-2 text-xs font-medium h-8 shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
                @click="updateMatchStatus(match, 'resolved')">
          <span>Mark as Resolved</span>
        </button>
        <button v-if="match.status === 'resolved'" class="inline-flex items-center justify-center rounded-md text-xs h-8 px-3 gap-1 text-secondary">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
            <path d="M20 6 9 17l-5-5"></path>
          </svg>
          <span>Resolved</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { toast } from 'vue3-toastify';
import { updateStatus } from '@/services/api/match';
import { getItem } from '@/services/api/item';

const props = defineProps({
  match: {
    type: Object,
    required: true
  }
});

// Computed classes
const statusClass = computed(() => {
  const classes = {
    'new': 'bg-primary/10 text-primary',
    'in-progress': 'bg-orange-500/10 text-orange-500',
    'resolved': 'bg-secondary/10 text-secondary'
  };
  return classes[props.match.status] || 'bg-muted/10 text-muted-foreground';
});

const statusLabel = computed(() => {
  const labels = {
    'new': 'New match',
    'in-progress': 'In Progress',
    'resolved': 'Resolved'
  };
  return labels[props.match.status] || 'Unknown status';
});

const matchScoreClass = computed(() => {
  if (props.match.match_score >= 90) return 'text-secondary';
  if (props.match.match_score >= 80) return 'text-primary';
  if (props.match.match_score >= 70) return 'text-orange-500';
  return 'text-red-500';
});

// Methods
async function updateMatchStatus(match, status) {
  try {
    await updateStatus(match.id, status);
    match.status = status;
    toast.success('Match status updated successfully');
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to update match status');
    console.error('Failed to update match status:', err);
  }
}

async function viewLostItem(match) {
  try {
    const response = await getItem(match.lost_item_id);
    // Handle item viewing logic here
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to load lost item');
    console.error('Failed to load lost item:', err);
  }
}

async function viewFoundItem(match) {
  try {
    const response = await getItem(match.found_item_id);
    // Handle item viewing logic here
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to load found item');
    console.error('Failed to load found item:', err);
  }
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleString();
}
</script>
