<template>
  <div>
    <button v-on:click="deleteFile(file)">Delete</button>
    <a v-bind:href="'/file/download/' + file.encodedName" v-on:click.prevent="downloadFile(file)">{{ file.name }}</a>
  </div>
</template>
<script>
// import axios from 'axios'


export default {
  props: ['file'],
  data() {
    return {
      downloadKey: 1
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
        const response = await fetch(`api.php?action=downloadFile&encodedName=${file.encodedName}`);
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
div {
  border: 1px solid black;
  padding: 10px;
  margin: 10px 0;
  min-width: 50vw;
}
</style>
