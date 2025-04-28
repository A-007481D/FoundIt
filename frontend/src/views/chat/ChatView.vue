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
              class="p-4 hover:bg-gray-50 cursor-pointer"
              :class="{ 'bg-gray-50': currentConversation?.id === conversation.id }"
            >
              <div class="flex items-center gap-4">
                <img
                  :src="getAvatarUrl(conversation.other_user.photo)"
                  :alt="conversation.other_user.name"
                  class="w-12 h-12 rounded-full object-cover"
                />
                <div class="flex-1">
                  <h3 class="font-medium">{{ conversation.other_user.name }}</h3>
                  <p class="text-sm text-gray-500 truncate">
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
import { ref, onMounted, computed, nextTick } from 'vue';
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

onMounted(async () => {
  await chatStore.fetchConversations();
  
  // If conversation ID is in the route, select it
  if (route.params.conversationId) {
    const conversation = sortedConversations.value.find(
      c => c.id === parseInt(route.params.conversationId)
    );
    if (conversation) {
      selectConversation(conversation);
    }
  }

  window.Echo.private(`App.Models.User.${currentUser.value.id}`)
    .listen('ChatEvent', (e) => {
      if (e.conversation_id === currentConversation.value.id) {
        chatStore.messages.push({
          id: e.id,
          content: e.content,
          sender: e.sender,
          created_at: e.created_at
        });
        nextTick(scrollToBottom);
      }
    });
});

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
