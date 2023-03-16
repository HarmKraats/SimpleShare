<template>
    <div class="footer">
        <p>SimpleShare - Share simple, use SimpleShare.</p>
    </div>
</template>

<script>
export default {
  name: 'FooterContainer'
}
</script>

<style scoped>
.footer{
    width: 100vw;
    background-color: #1e1e1e;
    color: #fff;
    text-align: center;
    padding: 1rem;
    height: var(--footer-height);
    display: flex;
    justify-content: center;
    align-items: center;
}

p{
    margin: 0;
}
</style>
