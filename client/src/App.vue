<template>
  <div id="app">
    <div class="page-wrapper">
      <HeaderContainer />
      <router-view />
    </div>
    <FooterContainer />
  </div>
</template>

<script>
import FooterContainer from '@/includes/Footer'
import HeaderContainer from '@/includes/header'

export default {
  name: 'App',

  components: {
    FooterContainer,
    HeaderContainer
  }
}
</script>

<style>
:root{
  --footer-height: 3.5rem;
}

.page-wrapper{
  padding-bottom: var(--footer-height);
}


*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  /* text-align: center; */
  color: #2c3e50;
  display: flex;
  align-items: center;
  flex-direction: column;
  min-height: 100vh;
  justify-content: space-between;
}

.logo {
  text-align: center;
}
</style>
