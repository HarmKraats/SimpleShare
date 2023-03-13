<template>
  <div>
      <button v-on:click="deleteFile(file)">Delete</button>
      <a v-bind:href="'/file/download/' + file.encodedName" v-on:click="downloadFile">{{ file.name }}</a>
      <file-downloader :key="downloadKey" ref="downloader"></file-downloader>
  </div>
</template>

<script>
// import axios from 'axios'
import FileDownloader from './FileDownloader'

export default {
  props: ['file'],
  data () {
    return {
      downloadKey: 1
    }
  },
  components: {
    FileDownloader
  },
  name: 'UploadedFile',
  methods: {
    deleteFile (file) {
      // Check if file has already been deleted
      if (!file.deleted) {
        this.$emit('delete-file', file)
      }
    },
    downloadFile (event) {
      event.preventDefault()
      let url = event.target.href
      this.downloadKey += 1

      this.$nextTick().then(() => {
        this.$refs.downloader.downloadFile(url)
      })
    }
  }
}
</script>

<style scoped>
  div{
    border: 1px solid black;
    padding: 10px;
    margin: 10px 0;
    min-width: 50vw;
  }
</style>
