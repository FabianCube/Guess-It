<template>
  <div class="text-input flex flex-row">
    <input 
      autocomplete="off" 
      id="btn-input" 
      type="text" 
      name="message" 
      class="form-control input-sm"
      placeholder="Escribe aquí..." 
      v-model="newMessage" 
      @keyup.enter="sendMessage"
      :disabled="!props.isChatEnabled">
    <button class="send-btn" id="btn-chat" @click="sendMessage">
      <img src="/storage/icons/icon-send.svg" alt="">
    </button>
    </input>
  </div>
</template>
<script setup>

import axios from 'axios';
import { defineProps, defineEmits, ref, onMounted } from 'vue';

//Takes the "user" props from <chat-form> … :user="user"></chat-form> in the parent Game.vue
const props = defineProps([ 'user' , 'isChatEnabled']);
const emits = defineEmits([ 'messagesent' ]);
const newMessage = ref('');


function sendMessage()
{
  console.log("[ChatForm.vue]:sendMessage:newMessage.value -> " + newMessage.value);

  //Emit a "messagesent" event including the user who sent the message along with the message content
  emits("messagesent", {
    user: props.user,
    message: newMessage.value
  });

  //Clear the input
  newMessage.value = '';
}

</script>

<style scoped>

@import './../style/chatForm.css';

</style>