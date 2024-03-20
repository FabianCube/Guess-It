<template>
    <div class="text-input flex flex-row">
      <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Escribe aquí..."
        v-model="newMessage"
        @keyup.enter="sendMessage"
      />
      <span class="input-group-btn">
        <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
          Send
        </button>
      </span>
    </div>
  </template>
  <script>
  export default {
    //Takes the "user" props from <chat-form> … :user="{{ Auth::user() }}"></chat-form> in the parent chat.blade.php.
    props: ["user"],
    data() {
      return {
        newMessage: "",
      };
    },
    methods: {
      sendMessage() {
        //Emit a "messagesent" event including the user who sent the message along with the message content
        this.$emit("messagesent", {
          user: this.user,
        //newMessage is bound to the earlier "btn-input" input field
          message: this.newMessage,
        });
        //Clear the input
        this.newMessage = "";
      },
    },
  };
  </script>
  
  <style scoped>
  .text-input
  {
    width: 100%;
    background-color: white;
  }

  #btn-input
  {
    width: 100%;
  }
  
  
  </style>
  