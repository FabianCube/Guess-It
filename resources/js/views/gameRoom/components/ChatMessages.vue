<template>
  <div class="round-container">
    <h2 class="round-title">RONDA {{ props.currentRound }} / {{ props.rounds }}</h2>
  </div>
  <ul class="chat" ref="chatContainer">
    <li class="left clearfix" v-for="message in props.messages" :key="message.id">
      <div v-if="!message.points" class="clearfix bubbles bubble-container"  >
        <div class="header" >
          <p :style="getUserColor(message)">
            {{ message.user.nickname }}:
          </p>
        </div>
        <p class="bubble-text">
          {{ message.message }}
        </p>
      </div>
      <div v-else>
        <div class="d-flex justify-content-between align-items-center py-2 mt-4 mb-3 correct-word">
          <div class="d-flex">
            <p class="me-3 mb-0" :style="{ color: message.user.color }">{{ message.user.nickname }}</p>
            <p class="mb-0">
              {{ message.message }}
            </p>
          </div>
          <div class="d-flex align-items-center">
            <p class="mb-0">+{{ message.points }}</p>
            <img src="/storage/icons/star-points.svg" alt="">
          </div>
        </div>
      </div>
    </li>
  </ul>
</template>

<script setup>
import { watch, ref, onMounted , computed } from 'vue';

const props = defineProps(['messages', 'rounds', 'currentRound']);
const chatContainer = ref(null);

watch(() => props.messages, (newMessages) => {
  messages.value = newMessages;
});

onMounted(() => {
  scrollToBottom();
});

function scrollToBottom() {
  const container = chatContainer.value;
  container.scrollBottom = container.scrollHeight;
}

function getUserColor (message) {
    console.log(message.user.color);
    return `color: ${message.user.color} !important;`;
};

</script>
<style scoped>

@import './../style/chatMessages.css';

</style>