<template>
    <ul class="chat" ref="chatContainer">
      <li class="left clearfix" v-for="message in messages" :key="message.id">
        <div class="clearfix">
          <div class="header">
            <strong>
              {{ message.user.name }}
            </strong>
          </div>
          <p>
            {{ message.message }}
          </p>
        </div>
      </li>
    </ul>
  </template>
  <script>
  export default {
    props: ["messages"],
    updated() {
      this.$nextTick(() => {
        const container = this.$refs.chatContainer;
        container.scrollTop = container.scrollHeight;
      });
    },
  };
  </script>
  <style scoped>
  
  .chat{
    max-height: 500px;
    overflow-y: auto;
  }
  
  </style>