<template>
	<div class="rect" v-bind:style="{backgroundColor: bgcolor}"></div>
</template>
<script>
	export default{
		//name: "rectangle",
		props: ['bgcolor']
	}
</script>
<style scoped>
	.rect{
		width: 50px;
		height: 50px;	
	}
</style>