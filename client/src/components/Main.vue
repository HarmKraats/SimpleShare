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
import UploadedFiles from './UploadedFilesList'
import UploadsContainer from './UploadsContainer'

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

.main{
  display: flex;
  align-items: center;
  flex-direction: column;
}

</style>
