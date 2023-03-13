<template>
  <div>
    <h3>Upload your files here</h3>

    <div class="upload-erea">

      <div>
        <input
        type="file"
        v-show="!uploadStarted"
        multiple
        v-bind:name="uploadName"
        @change="fileSelected"
        />
        <p v-show="uploadStarted">Uploading...</p>
      </div>
      <div class="buttons">
        <button v-show="!uploadStarted" v-on:click="startUpload">
          Start upload
        </button>
        <button v-show="uploadStarted" v-on:click="cancelUpload">
          Cancel upload
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
const CancelToken = axios.CancelToken
const source = CancelToken.source()
export default {
  name: 'Main',
  data () {
    return {
      uploadStarted: false,
      uploadName: 'files',
      uploadUrl: '/api/upload',
      formData: null
    }
  },
  methods: {
    fileSelected (event) {
      if (event.target.files.length === 0) {
        return
      }
      let files = event.target.files
      let name = event.target.name
      let formData = new FormData()

      for (let index = 0; index < files.length; index++) {
        formData.append(name, files[index], files[index].name)
      }
      this.$set(this, 'formData', formData)
    },

    startUpload () {
      this.$set(this, 'uploadStarted', true)
      this.uploadData(this.formData)
    },

    cancelUpload () {
      if (this.uploadStarted) {
        source.cancel()
      }
      this.$set(this, 'uploadStarted', false)
    },
    uploadData (formData) {
      if (this.formData === null) {
        return
      }
      axios
        .post(this.uploadUrl, formData, {
          cancelToken: source.token
        })
        .then(response => {
          if (response.status >= 200 && response.status < 300) {
            this.updateFilesList(response.data)
            this.$set(this, 'formData', null)
          } else {
            alert('File not uploaded. Please check the file types' + response)
          }
        })
        .catch((e) => {
          alert('Error occured' + e)
        })
        .then(() => {
          this.$set(this, 'uploadStarted', false)
        })
    },
    updateFilesList (files) {
      this.$emit('files-uploaded', files)
    }
  }
}
</script>

<style scoped>
  .buttons{
    margin-top: 10px;
  }

  input {
    border: none;
    outline: transparent;
  }

  .upload-erea{
    display: flex;
    flex-direction: column;
    align-items: flex-start
  }
</style>
