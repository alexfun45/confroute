<template>
  <div>
    Content page with {{text}}
  </div>
</template>

<script>
export default {
  name: 'Page',
  data: function() {
    return {
      text: 'Hello'
    }
  }
}
</script>