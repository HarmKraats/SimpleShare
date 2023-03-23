<template>
  <div>
    <h3>Click here to upload</h3>

    <div class="upload-erea">
      
      <div class="uploader">
        <input type="file" v-show="!uploadStarted" multiple 
        v-bind:name="uploadName" @change="fileSelected" />
        <p v-show="uploadStarted">Uploading...</p>
        <a target="_blank" v-show="showLink" v-bind:href="'/#/files/' + this.filesUrl" >http://localhost/#/files/{{ this.filesUrl }}</a>
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
  data() {
    return {
      uploadStarted: false,
      uploadName: 'files',
      uploadUrl: '/api?action=uploadFile',
      formData: null,
      files: [],
      filesUrl: '',
      showLink: false
    }
  },
  methods: {
    fileSelected(event) {
      if (event.target.files.length === 0) {
        return
      }
      this.$set(this, 'showLink', false)
      let files = event.target.files
      let formData = new FormData()

      for (let index = 0; index < files.length; index++) {
        let key = 'file_' + index
        formData.append(key, files[index], files[index].name)
        // make an array of all files
        this.files.push(files[index])
      }

      // console.log(this.files)

      this.$set(this, 'formData', formData)
    },

    startUpload() {
      this.$set(this, 'uploadStarted', true)
      this.uploadData(this.formData)
    },

    cancelUpload() {
      if (this.uploadStarted) {
        source.cancel()
      }
      this.$set(this, 'uploadStarted', false)
    },

    uploadData(formData) {
      if (this.formData === null) {
        return
      }
      axios
        .post(this.uploadUrl, this.formData, {
          cancelToken: source.token
        })
        .then(response => {
          // console.log(response)
          if (response.status >= 200 && response.status < 300) {
            this.updateFilesList(response.data)
            this.$set(this, 'formData', null)

            axios.
              get('/api?action=getFilesList&id=' + response.data[0]._id)
              .then(response => {
                this.$set(this, 'showLink', true)
                this.filesUrl = response.data[0].url
              })


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

    updateFilesList(files) {
      this.$emit('files-uploaded', files)
    }
  },
  mounted() {
    console.log(this.showLink);
    
  }
}
</script>

<style scoped>
.buttons {
  margin-top: 10px;
}

input {
  border: none;
  outline: transparent;
}

.upload-erea {
  display: flex;
  flex-direction: column;
  align-items: flex-start
}

h3 {
  margin-bottom: 5px;
}
</style>
