<template>
    <div>
        <h1>Bestandenlijst voor {{ url }}</h1>
        <uploaded-file v-for="file in files" v-bind:key="file.id"
            v-bind:file.sync="file" v-on:delete-file="deleteFile"></uploaded-file>
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
            anyFiles: true
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
                    console.log(response)
                    this.$set(this, 'files', response.data)
                    this.files = response.data;
                })
        },
    },
    mounted() {
        this.fetchFiles()
    }
}
</script>
