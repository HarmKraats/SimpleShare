<template>
  <div>
    <div>
      <a>{{ file.name }}</a>
      <button>Delete</button>
    </div>
  </div>
</template>

<script>
// import axios from 'axios'
export default {
  props: ['file'],
  data () {
    return {}
  },
  name: 'UploadedFile',
  methods: {}
}
</script>

<style scoped></style>
