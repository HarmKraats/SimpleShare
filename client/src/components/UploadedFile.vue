<template>
  <div class="main-wrapper">
    <div class="wrapper">
      <div class="left">

        <button v-if="showDelete" v-on:click="deleteFile(file)">Delete</button>
        <a v-bind:href="'/file/download/' + file.encodedName"
          v-on:click.prevent="downloadFile(file)">{{ file.name }}</a>
      </div>
      <div class="right">
        <span>{{ file.share_list_id }}</span>
      </div>
    </div>
  </div>
</template>

<script>
// import axios from 'axios'


export default {
  props: ['file' ,'showDelete'],
  data() {
    return {
      downloadKey: 1,
      fileFolder: null
    }
  },

  name: 'UploadedFile',
  methods: {
    deleteFile(file) {
      // Check if file has already been deleted
      if (!file.deleted) {
        this.$emit('delete-file', file)
      }
    },
    async downloadFile(file) {
      try {
        const response = await fetch(`https://api.simpleshare.harmvandekraats.nl/api.php?action=downloadFile&encodedName=${file.encodedName}`);
        const blob = await response.blob();
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = file.name;
        link.click();
      } catch (error) {
        console.error('Error downloading file:', error);
      }
    },
  }
}
</script>

<style scoped>
.wrapper {
  border: 1px solid #000;
  padding: 10px 20px;
  margin: 10px 0;
  width: min(900px, 90vw);
  display: flex;
  justify-content: space-between;
  align-items: center;
  overflow: auto;
}

a{
  color: #000;
  text-decoration: none;
  margin-left: 10px;
}

.main-wrapper{
  display: flex;
  align-items: center;
  flex-direction: column;
}
</style>
