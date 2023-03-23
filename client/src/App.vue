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

body{
  /* background-image: linear-gradient(90deg, rgba(30, 93, 93, 1), rgba(43, 18, 51, 1)); */
  overflow-x: hidden;
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
  color: #000;
  display: flex;
  align-items: center;
  flex-direction: column;
  min-height: 100vh;
  justify-content: space-between;
}

.logo {
  text-align: center;
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
  transition: .3s;
}

button:hover,
button:focus,
button:active{
  background-color: #6a4bce;
}
</style>
