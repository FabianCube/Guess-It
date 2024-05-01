<template>
  <div class="round-container">
    <h2 class="round-title">RONDA {{ props.currentRound }} / {{ props.rounds }}</h2>
  </div>
  <ul class="chat" ref="chatContainer">
    <li class="left clearfix bubbles" v-for="message in props.messages" :key="message.id">
      <div class="clearfix bubble-container">
        <div class="header">
          <strong>
            {{ message.user.nickname }}:
          </strong>
        </div>
        <p class="bubble-text">
          {{ message.message }}
        </p>
      </div>
    </li>
  </ul>
</template>

<script setup>
import { watch, ref, onMounted } from 'vue';

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

.bubbles
{
  border: solid 2px black;
  border-radius: 12px;
  margin-top: 10px;
  width: fit-content;
}

.bubble-container
{
  display: inline-flex;
  align-items: center;
  flex-flow: row;
  min-height: 30px;
  height: auto;
  padding: 0 10px 0 10px;
}
.header
{
  font-family: "Lilita One", sans-serif;
  margin-right: 7px;
}
.bubble-text
{
  /*overflow-wrap: normal;*/
  word-break: break-word;
}


/* ROUND */

.round-container
{
  padding: 0 10px 0 10px;
}

.round-title
{
  font-family: "Lilita One", sans-serif;
}

</style>