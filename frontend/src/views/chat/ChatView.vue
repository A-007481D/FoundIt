<template>
  <div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Conversations List -->
        <div class="w-full lg:w-1/3 bg-white rounded-lg shadow">
          <div class="p-4 border-b">
            <h2 class="text-xl font-semibold">Messages</h2>
          </div>
          <div class="divide-y max-h-[calc(100vh-200px)] overflow-y-auto">
            <div
              v-for="conversation in sortedConversations"
              :key="conversation.id"
              @click="selectConversation(conversation)"
              class="p-4 hover:bg-gray-50 cursor-pointer transition-colors"
              :class="[
                currentConversation?.id === conversation.id ? 'bg-gray-50' : '',
                conversation.unread_count > 0 ? 'bg-blue-100 border-l-4 border-blue-400' : ''
              ]"
            >
              <div class="flex items-center gap-4">
                <img
                  :src="getAvatarUrl(conversation.other_user.photo)"
                  :alt="conversation.other_user.name"
                  class="w-12 h-12 rounded-full object-cover"
                />
                <div class="flex-1">
                  <h3 :class="conversation.unread_count > 0 ? 'font-bold text-blue-900' : 'font-medium'">
                    {{ conversation.other_user.name }}
                  </h3>
                  <p :class="conversation.unread_count > 0 ? 'font-semibold text-blue-700' : 'text-sm text-gray-500'" class="truncate">
                    {{ conversation.last_message?.content || 'No messages yet' }}
                  </p>
                </div>
                <div v-if="conversation.unread_count > 0" class="bg-blue-500 text-white text-xs rounded-full px-2 py-1">
                  {{ conversation.unread_count }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Chat Area -->
        <div class="w-full lg:w-2/3 bg-white rounded-lg shadow">
          <template v-if="currentConversation">
            <!-- Chat Header -->
            <div class="p-4 border-b flex items-center justify-between">
              <div class="flex items-center gap-4">
                <img
                  :src="getAvatarUrl(currentConversation.other_user.photo)"
                  :alt="currentConversation.other_user.name"
                  class="w-12 h-12 rounded-full object-cover"
                />
                <h2 class="text-xl font-semibold">
                  {{ currentConversation.other_user.name }}
                </h2>
              </div>
            </div>

            <!-- Messages -->
            <div
              ref="messagesContainer"
              class="p-4 space-y-4 max-h-[calc(100vh-300px)] overflow-y-auto"
            >
              <div
                v-for="message in messages"
                :key="message.id"
                class="flex"
                :class="{ 'justify-end': message.sender.id === currentUser.id }"
              >
                <div class="group relative">
                  <div
                    class="max-w-full rounded-lg p-3"
                    :class="{
                      'bg-blue-500 text-white': message.sender.id === currentUser.id,
                      'bg-gray-100': message.sender.id !== currentUser.id
                    }"
                  >
                    <p>{{ message.content }}</p>
                    <span class="text-xs opacity-75">
                      {{ formatDate(message.created_at) }}
                    </span>
                  </div>
                  <button
                    v-if="message.sender.id !== currentUser.id"
                    @click="openReportModal(message)"
                    class="absolute top-0 right-0 -mt-2 -mr-2 hidden group-hover:block bg-white rounded-full p-1 shadow"
                  >
                    <i class="fas fa-flag text-gray-500 hover:text-red-500"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Message Input -->
            <div class="p-4 border-t">
              <form @submit.prevent="sendMessage" class="flex gap-4">
                <input
                  v-model="newMessage"
                  type="text"
                  placeholder="Type your message..."
                  class="flex-1 rounded-lg border p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <button
                  type="submit"
                  class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  Send
                </button>
              </form>
            </div>
          </template>

          <div
            v-else
            class="h-full flex flex-col items-center justify-center text-gray-500 p-8"
          >
            <MessageSquare class="h-16 w-16 mb-4 text-primary/20" />
            <h3 class="text-xl font-semibold mb-2">Welcome to Messages</h3>
            <p class="text-center text-gray-500 max-w-md">
              Select a conversation from the list to start chatting, or contact an item owner through their listing to start a new conversation.
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Report Modal -->
    <div
      v-if="showReportModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Report Message</h3>
        <textarea
          v-model="reportReason"
          placeholder="Why are you reporting this message?"
          class="w-full h-32 p-2 border rounded-lg mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
        ></textarea>
        <div class="flex justify-end gap-4">
          <button
            @click="showReportModal = false"
            class="px-4 py-2 text-gray-600 hover:text-gray-800"
          >
            Cancel
          </button>
          <button
            @click="submitReport"
            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600"
          >
            Submit Report
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch, onUnmounted } from 'vue';
import { useRoute } from 'vue-router';
import { toast } from 'vue3-toastify';
import ChatService from '@/services/api/chat';
import { useChatStore } from '@/stores/chat.store';
import { MessageSquare } from 'lucide-vue-next';
import { useAuthStore } from '@/stores/auth.store';

const route = useRoute();
const chatStore = useChatStore();
const authStore = useAuthStore();

const loading = ref(false);
const messages = ref([]);
const newMessage = ref('');
const conversation = ref(null);
const typingUsers = ref([]);
const showReportModal = ref(false);
const reportReason = ref('');
const reportingMessage = ref(null);

// Computed values
const currentUser = computed(() => authStore.user);
const currentConversation = computed(() => conversation.value);
const isTyping = computed(() => typingUsers.value.length > 0);
const typingMessage = computed(() => {
  if (typingUsers.value.length === 1) {
    return `${typingUsers.value[0]} is typing...`;
  }
  return `${typingUsers.value.length} users are typing...`;
});

// Methods
async function fetchConversation() {
  loading.value = true;
  try {
    const response = await ChatService.getConversation(route.params.id);
    conversation.value = response.data.conversation;
    messages.value = response.data.messages || [];
    chatStore.updateUnreadCount();
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to fetch conversation');
    console.error('Failed to fetch conversation:', err);
  } finally {
    loading.value = false;
  }
}

async function sendMessage() {
  if (!newMessage.value.trim()) return;
  
  try {
    const response = await ChatService.sendMessage(route.params.id, newMessage.value);
    messages.value.push(response.data.message);
    newMessage.value = '';
    chatStore.updateUnreadCount();
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to send message');
    console.error('Failed to send message:', err);
  }
}

function startTyping() {
  ChatService.startTyping(route.params.id);
}

function stopTyping() {
  ChatService.stopTyping(route.params.id);
}

function formatDate(date) {
  if (!date) return '';
  return new Date(date).toLocaleString();
}

function openReportModal(message) {
  reportingMessage.value = message;
  showReportModal.value = true;
  reportReason.value = '';
}

async function submitReport() {
  if (!reportReason.value.trim() || !reportingMessage.value) {
    toast.error('Please provide a reason for reporting');
    return;
  }
  
  try {
    await ChatService.reportMessage(reportingMessage.value.id, reportReason.value);
    toast.success('Message reported successfully');
    showReportModal.value = false;
    reportingMessage.value = null;
    reportReason.value = '';
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to report message');
    console.error('Failed to report message:', err);
  }
}

// Watch for route changes
watch(() => route.params.id, (newId) => {
  if (newId) {
    fetchConversation();
  }
});

// Load conversation on component mount
onMounted(() => {
  if (route.params.id) {
    fetchConversation();
  }
  
  // Listen for new messages
  ChatService.onNewMessage((message) => {
    messages.value.push(message);
    chatStore.updateUnreadCount();
  });
  
  // Listen for typing events
  ChatService.onTyping((users) => {
    typingUsers.value = users;
  });
});

// Cleanup event listeners on component unmount
onUnmounted(() => {
  ChatService.offNewMessage();
  ChatService.offTyping();
});
</script>
