<template>
  <ul class="chat" ref="chatContainer">
    <li class="left clearfix" v-for="message in props.messages" :key="message.id">
      <div class="clearfix bg-primary">
        <div class="header">
          <strong>
            {{ message.user.nickname }}
          </strong>
        </div>
        <p>
          {{ message.message }}
        </p>
      </div>
    </li>
  </ul>
</template>

<script setup>
import { onUpdated, nextTick, watch, ref, onMounted } from 'vue';

const props = defineProps(['messages'])
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
  height: 100%;
  width: 100%;
  overflow-y: auto;
  display: flex;
  justify-content: end;
  flex-flow: column;
  list-style: none;
}
</style>