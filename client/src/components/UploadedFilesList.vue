
<template>
  <div>
    <h2>Files List</h2>

    <ul>
      <uploaded-file
        v-for="file in files"
        v-bind:key="file.id"
        v-bind:file.sync="file"
        v-on:delete-file="deleteFile"
      ></uploaded-file>
    </ul>
  </div>
</template>

<script>
import axios from 'axios'
import UploadedFile from './UploadedFile'

export default {
  name: 'UploadedFilesList',
  data () {
    return {
      files: []
    }
  },
  components: {
    UploadedFile
  },
  methods: {
    fetchFiles () {
      axios.get('/api.php?action=getFiles').then(response => {
        // console.log(response.data)
        this.$set(this, 'files', response.data)
      })
    },

    filesUploaded (files) {
      this.fetchFiles()
    },

    deleteFile (file) {
      if (confirm('Are you sure you want to delete this file?')) {
        axios.delete('/api.php?action=deleteFile&id=' + file.id)
          .then((response) => {
            let fileIndex = this.files.indexOf(file)
            this.files.splice(fileIndex, 1)
            // console.log(response);
            
          })
          .catch(error => {
            console.log('Error deleting file ' + error)
          })
      }
    }
  },

  mounted () {
    this.fetchFiles()
  }
}
</script>
