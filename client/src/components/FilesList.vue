<template>
    <div class="center">
        <h1>Files for https://localhost/#/files/{{ url }}</h1>


        <button class="button" v-on:click="downloadAll()">
            download all files
        </button>

        <div class="buttons">
            <span v-show="downloadStarted">
                Downloading...
            </span>
        </div>

        <uploaded-file v-for="file in files" v-bind:key="file.id"
            v-bind:file.sync="file" v-bind:showDelete="false"></uploaded-file>


    </div>
</template>

<script>
import axios from 'axios';
import UploadedFile from './UploadedFile';

export default {
    name: 'FilesList',
    data() {
        return {
            url: '',
            files: [],
            anyFiles: true,
            downloadStarted: false
        }
    },
    components: {
        UploadedFile
    },
    methods: {
        fetchFiles() {
            this.url = this.$route.params.url;
            axios.get(`/files/${this.url}`)
                .then(response => {
                    this.$set(this, 'files', response.data)
                    this.files = response.data;
                })
        },
        downloadAll() {
            this.downloadStarted = true;
            axios.get('/api?action=downloadSelected&url=' + this.url, { responseType: 'blob' })
                .then(response => {
                    console.log(response);
                    const url = URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'simpleShareDownload.zip');
                    document.body.appendChild(link);
                    link.click();
                    this.downloadStarted = false;
                })
                .catch(error => {
                    console.error(error);
                });
        },
    },
    mounted() {
        this.fetchFiles()
    }
}
</script>

<style scoped>
.center {
    text-align: center;
}
</style>
