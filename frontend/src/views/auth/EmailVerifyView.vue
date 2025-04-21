<template>
    <div class="min-h-screen flex items-center justify-center">
        <div v-if="loading">Vérification en cours…</div>
        <div v-else-if="error" class="text-red-600">{{ error }}</div>
        <div v-else class="text-green-600">{{ message }}</div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const route = useRoute();
const loading = ref(true);
const error = ref('');
const message = ref('');

onMounted(async () => {
    console.log('Params:', route.params);
    console.log('Query:', route.query);
    const { id, hash } = route.params;
    const { expires, signature } = route.query;

    // Build the API URL
    const url = `http://localhost:8000/api/email/verify/${id}/${hash}`
        + `?expires=${expires}&signature=${signature}`;
    console.log('Calling:', url);

    try {
        const res = await axios.post(url);
        message.value = res.data.message;
    } catch (err) {
        console.error('Verify error:', err);
        error.value = err.response?.data?.message || 'Échec de la vérification.';
    } finally {
        loading.value = false;
    }
});
</script>
