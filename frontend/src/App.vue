<template>
    <!-- router-view is where all your <LoginView/>, <RegisterView/>, etc. get injected -->
    <NavbarView />
    <router-view />
</template>

<script setup>
// (no extra code needed here)
import NavbarView from "@/components/NavbarView.vue";
</script>

<style>
/* optional global styles */
</style>
