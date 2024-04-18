<template>
  <ul class="chat" ref="chatContainer">
    <li class="left clearfix" v-for="message in props.messages" :key="message.id">
      <div class="clearfix">
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
import { onUpdated, nextTick, ref } from 'vue';

const props = defineProps(['messages'])
const chatContainer = ref(null);

onUpdated()
{
  console.log("[ChatMessages]: Chat updated!")
  
  nextTick(() => {
    const container = chatContainer.value;
    container.scrollTop = container.scrollHeight;
  });
}

</script>
<style scoped>
.chat {
  height: 100%;
  width: 100%;
  overflow-y: auto;
}
</style>