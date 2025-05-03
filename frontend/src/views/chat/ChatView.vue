<template>
  <div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
      <!-- Mobile header - shown only on small screens -->
      <div class="block md:hidden mb-4">
        <button 
          v-if="currentConversation && showMessages" 
          @click="showMessages = false"
          class="flex items-center gap-2 text-primary font-medium"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
          Back to conversations
        </button>
        <h1 v-else class="text-xl font-bold">Messages</h1>
      </div>

      <div class="flex flex-col md:flex-row gap-4 md:gap-8">
        <!-- Conversations List -->
        <div 
          class="w-full md:w-1/3 bg-white rounded-lg shadow overflow-hidden"
          :class="{'hidden md:block': showMessages}"
        >
          <div class="p-3 md:p-4 border-b hidden md:block">
            <h2 class="text-lg md:text-xl font-semibold">Messages</h2>
          </div>
          <div class="divide-y max-h-[calc(100vh-180px)] md:max-h-[calc(100vh-200px)] overflow-y-auto">
            <div
              v-for="conversation in sortedConversations"
              :key="conversation.id"
              @click="selectConversation(conversation); showMessages = true;"
              class="p-3 md:p-4 hover:bg-gray-50 cursor-pointer transition-colors flex items-center gap-3 md:gap-4"
              :class="[
                currentConversation?.id === conversation.id ? 'bg-gray-50' : '',
                conversation.unread_count > 0 ? 'bg-blue-50 border-l-4 border-blue-400' : ''
              ]"
            >
                <img
                  :src="getAvatarUrl(conversation.other_user.photo)"
                  :alt="conversation.other_user.name"
                class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover flex-shrink-0"
                />
              <div class="flex-1 min-w-0">
                <h3 :class="conversation.unread_count > 0 ? 'font-bold text-blue-900' : 'font-medium'" class="truncate">
                    {{ conversation.other_user.name }}
                  </h3>
                  <p :class="conversation.unread_count > 0 ? 'font-semibold text-blue-700' : 'text-sm text-gray-500'" class="truncate">
                    {{ conversation.last_message?.content || 'No messages yet' }}
                  </p>
                </div>
              <div v-if="conversation.unread_count > 0" class="bg-blue-500 text-white text-xs rounded-full px-2 py-1 flex-shrink-0">
                  {{ conversation.unread_count }}
              </div>
            </div>
          </div>
        </div>

        <!-- Chat Area -->
        <div 
          class="w-full md:w-2/3 bg-white rounded-lg shadow flex flex-col h-[calc(100vh-120px)] md:h-[calc(100vh-180px)]"
          :class="{'hidden md:flex': !showMessages && !currentConversation, 'flex': showMessages || currentConversation && $screen.md}"
        >
          <template v-if="currentConversation">
            <!-- Chat Header -->
            <div class="p-3 md:p-4 border-b flex items-center justify-between sticky top-0 bg-white z-10">
              <div class="flex items-center gap-3 md:gap-4">
                <img
                  :src="getAvatarUrl(currentConversation.other_user.photo)"
                  :alt="currentConversation.other_user.name"
                  class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover"
                />
                <h2 class="text-lg md:text-xl font-semibold">
                  {{ currentConversation.other_user.name }}
                </h2>
              </div>
            </div>

            <!-- Messages -->
            <div
              ref="messagesContainer"
              class="p-3 md:p-4 space-y-3 md:space-y-4 overflow-y-auto flex-grow"
            >
              <div
                v-for="message in messages"
                :key="message.id"
                class="flex"
                :class="{ 'justify-end': message.sender.id === currentUser.id }"
              >
                <div class="group relative max-w-[85%] md:max-w-[70%]">
                  <div
                    class="rounded-lg p-2 md:p-3"
                    :class="{
                      'bg-blue-500 text-white': message.sender.id === currentUser.id,
                      'bg-gray-100': message.sender.id !== currentUser.id
                    }"
                  >
                    <p>{{ message.content }}</p>
                    <span class="text-xs opacity-75 block text-right">
                      {{ formatDate(message.created_at) }}
                    </span>
                  </div>
                  <button
                    v-if="message.sender.id !== currentUser.id"
                    @click="openReportModal(message)"
                    class="absolute top-0 right-0 -mt-2 -mr-2 hidden group-hover:block bg-white rounded-full p-1 shadow"
                    aria-label="Report message"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500 hover:text-red-500"><path d="M4.12 4.12a10 10 0 0 0-.17 13.52L12 12l4.05-4.05c.29-.29.29-.77 0-1.06a.75.75 0 0 0-1.06 0L12 10.06 8.73 6.79a.75.75 0 0 0-1.06 0 .75.75 0 0 0 0 1.06L10.94 12l-7.76 7.76a10 10 0 0 1-.17-13.52Z"></path><path d="m19.67 4.33-2.51 2.51"></path><path d="M22 2 12 12"></path></svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Message Input -->
            <div class="p-3 md:p-4 border-t sticky bottom-0 bg-white">
              <form @submit.prevent="sendMessage" class="flex gap-2 md:gap-4">
                <input
                  v-model="newMessage"
                  type="text"
                  placeholder="Type your message..."
                  class="flex-1 rounded-lg border p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base"
                />
                <button
                  type="submit"
                  class="bg-blue-500 text-white px-3 md:px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 flex-shrink-0"
                >
                  Send
                </button>
              </form>
            </div>
          </template>

          <div
            v-else
            class="h-full flex flex-col items-center justify-center text-gray-500 p-4 md:p-8"
          >
            <MessageSquare class="h-12 w-12 md:h-16 md:w-16 mb-4 text-primary/20" />
            <h3 class="text-lg md:text-xl font-semibold mb-2">Welcome to Messages</h3>
            <p class="text-center text-gray-500 max-w-md text-sm md:text-base">
              Select a conversation from the list to start chatting, or contact an item owner through their listing to start a new conversation.
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Report Modal -->
    <div
      v-if="showReportModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
    >
      <div class="bg-white rounded-lg p-4 md:p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Report Message</h3>
        <textarea
          v-model="reportReason"
          placeholder="Why are you reporting this message?"
          class="w-full h-32 p-2 border rounded-lg mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base"
        ></textarea>
        <div class="flex justify-end gap-4">
          <button
            @click="showReportModal = false"
            class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm md:text-base"
          >
            Cancel
          </button>
          <button
            @click="submitReport"
            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm md:text-base"
          >
            Submit Report
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, nextTick, onUnmounted } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth.store';
import { useChatStore } from '@/stores/chat.store';
import { MessageSquare } from 'lucide-vue-next';
import { API_BASE_URL } from '@/config';

const route = useRoute();
const authStore = useAuthStore();
const chatStore = useChatStore();

const messagesContainer = ref(null);
const newMessage = ref('');
const showReportModal = ref(false);
const reportReason = ref('');
const messageToReport = ref(null);
const showMessages = ref(false); // For mobile view toggle

const storageBase = API_BASE_URL.replace(/\/api$/, '');
function getAvatarUrl(photo) {
  if (!photo) return '/img/default-profile.png';
  if (photo.startsWith('http')) return photo;
  if (photo.startsWith('/storage')) return `${storageBase}${photo}`;
  return `${storageBase}/storage/${photo}`;
}

const currentUser = computed(() => authStore.user);
const sortedConversations = computed(() => chatStore.sortedConversations);
const currentConversation = computed(() => chatStore.currentConversation);
const messages = computed(() => chatStore.messages);

// Detect screen size for mobile/desktop view
const $screen = computed(() => ({
  sm: window.innerWidth >= 640,
  md: window.innerWidth >= 768,
  lg: window.innerWidth >= 1024,
  xl: window.innerWidth >= 1280
}));

onMounted(async () => {
  await chatStore.fetchConversations();
  
  // If conversation ID is in the route, select it
  if (route.params.conversationId) {
    const conversation = sortedConversations.value.find(
      c => c.id === parseInt(route.params.conversationId)
    );
    if (conversation) {
      selectConversation(conversation);
      showMessages.value = true;
    }
  }

  // Listen for screen resize events
  window.addEventListener('resize', handleResize);

  // Initial screen size check
  handleResize();

  // Echo for real-time messaging
  if (window.Echo) {
    window.Echo.private(`App.Models.User.${currentUser.value.id}`)
      .listen('ChatEvent', (e) => {
        if (e.conversation_id === currentConversation.value?.id) {
          chatStore.messages.push({
            id: e.id,
            content: e.content,
            sender: e.sender,
            created_at: e.created_at
          });
          nextTick(scrollToBottom);
        }
      });
  }
});

// Clean up event listeners
onUnmounted(() => {
  window.removeEventListener('resize', handleResize);
});

const handleResize = () => {
  $screen.value = {
    sm: window.innerWidth >= 640,
    md: window.innerWidth >= 768,
    lg: window.innerWidth >= 1024,
    xl: window.innerWidth >= 1280
  };
  
  // Auto-adjust mobile view based on screen size
  if ($screen.value.md) {
    showMessages.value = false; // Reset on desktop view
  }
};

const selectConversation = async (conversation) => {
  chatStore.setCurrentConversation(conversation);
  await chatStore.fetchMessages(conversation.id);
  await nextTick();
  scrollToBottom();
};

const sendMessage = async () => {
  if (!newMessage.value.trim()) return;
  
  try {
    await chatStore.sendMessage({
      conversationId: currentConversation.value.id,
      content: newMessage.value
    });
    newMessage.value = '';
    await nextTick();
    scrollToBottom();
  } catch (error) {
    console.error('Error sending message:', error);
  }
};

const scrollToBottom = () => {
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
}
};

const formatDate = (date) => {
  return new Date(date).toLocaleString();
};

const openReportModal = (message) => {
  messageToReport.value = message;
  showReportModal.value = true;
  reportReason.value = '';
};

const submitReport = async () => {
  if (!reportReason.value.trim()) return;

  try {
    await chatStore.reportMessage({
      messageId: messageToReport.value.id,
      reason: reportReason.value
    });
    showReportModal.value = false;
    messageToReport.value = null;
    reportReason.value = '';
  } catch (error) {
    console.error('Error reporting message:', error);
  }
};
</script>
