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
.chat {
  height: 90%;
  width: 100%;
  overflow-y: auto;
  display: flex;
  justify-content: end;
  flex-flow: column;
  list-style: none;
  padding: 10px;
}

.bubbles {
  border: solid 2px black;
  font-size: 16px;
  border-radius: 12px;
  margin-top: 10px;
  width: fit-content;
  box-shadow: 0px 2px 3px 0px rgba(0, 0, 0, 0.25);
}

.bubble-container {
  display: inline-flex;
  align-items: center;
  flex-flow: row;
  min-height: 30px;
  height: auto;
  padding: 0 10px 0 10px;
}

.header {
  font-family: "Lilita One", sans-serif;
  margin-right: 7px;
}

.bubble-text {
  /*overflow-wrap: normal;*/
  word-break: break-word;
}

.correct-word {
  border-top: 1px solid #A0A0A0 !important;
  border-bottom: 1px solid #A0A0A0 !important;
  font-family: "Lilita One", sans-serif;
  border: none;
  font-size: 18px;
  color: #38C62C;
}


/* ROUND */

.round-container {
  padding: 0 10px 0 10px;
}

.round-title {
  font-family: "Lilita One", sans-serif;
}
</style>