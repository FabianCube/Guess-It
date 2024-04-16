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
      @keyup.enter="sendMessage">
    <button class="send-btn" id="btn-chat" @click="sendMessage">
      <img src="/storage/icons/icon-send.svg" alt="">
    </button>

    </input>
  </div>
</template>
<script setup>

import axios from 'axios';
import { defineProps, defineEmits, ref } from 'vue';

//Takes the "user" props from <chat-form> … :user="user"></chat-form> in the parent Game.vue
const props = defineProps([ "user" ]);
const emits = defineEmits([ "messagesent" ]);
console.log(props)
const newMessage = ref('');

function sendMessage()
{
  //Emit a "messagesent" event including the user who sent the message along with the message content
  emits("messagesent", {
    user: props.user,
    //newMessage is bound to the earlier "btn-input" input field
    message: newMessage.value,
  });

  console.log("[INFO]: User: " + props.user.nickname);
  console.log("[INFO]: Content message: " + newMessage.value);

  //Clear the input
  newMessage.value = '';
}

</script>

<style scoped>
.text-input {
  width: 100%;
  background-color: white;
  position: relative;
}

#btn-input {
  width: 100%;
  border-radius: 22px;
}

.send-btn {
  position: absolute;
  border: none;
  background-color: transparent;
  right: 0;
  top: 3px;
}

input:focus {
  outline: none !important;
  outline-width: 0 !important;
  box-shadow: none;
  -moz-box-shadow: none;
  -webkit-box-shadow: none;
  border: grey 1px solid;
}
</style>