<template>
    <div class="main">
        <div>
            <uploads-container
                v-on:files-uploaded="filesUploaded"></uploads-container>
        </div>
        <div>
            <uploaded-files ref="filesList"
                v-bind:files.sync="files"></uploaded-files>
        </div>
    </div>
</template>

<script>
// import axios from "axios";
import UploadedFiles from '@/components/UploadedFilesList'
import UploadsContainer from '@/components/UploadsContainer'

export default {
  name: 'Main',
  data () {
    return {
      files: []
    }
  },
  components: {
    UploadedFiles,
    UploadsContainer
  },
  computed: {
    filesList: {
      get () {
        return this.$refs.filesList.files
      },
      set (files) {
        this.$refs.filesList.files = files
      }
    }
  },
  methods: {
    filesUploaded (files) {
      // this.filesList = files \\This does not work
      this.$refs.filesList.filesUploaded(files)
    }
  }
}
</script>

<style>
body{
  overflow-x: hidden;
}
.main{
  display: flex;
  align-items: center;
  flex-direction: column;
}
h1,
h2,
p {
  margin-bottom: 10px;
}
h2 {
  font-weight: normal;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #338e65;
  text-decoration: none;
}

button{
  outline: transparent;
  border: none;
  background-color: #8a66e4;
  color: #fff;
  padding: .5rem;
  border-radius: 999px;
  cursor: pointer;
  font-weight: bold;
  margin: 10px 0;
}
</style>
